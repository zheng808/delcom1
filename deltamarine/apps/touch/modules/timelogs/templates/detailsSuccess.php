<?php use_helper('Form', 'Javascript'); ?>
<?php 
  remote_function();  //ad prototype dependencies
  echo javascript_tag("
  function updateLabour(labour_id){
    $('labour_type_id').value = labour_id;
    $$('.labour-item a').each(function(el,idx){
      if (el.id == 'labour_option_' + labour_id){
        el.addClassName('active');
      } else {
        el.removeClassName('active');
      }   
    });
  }
  function updateTimeHours(hours){
    $('hours').value = hours;
    $$('#time_hours a').each(function(el,idx){
      if (el.id == 'hours_' + hours){
        el.addClassName('active');
      } else {
        el.removeClassName('active');
      }   
    });
    if ($('minutes').value=='0') {
      updateTimeMinutes(0);
    } else {
      calculateTime();
    }
  }

  function updateTimeMinutes(mins){
    $('minutes').value = mins;
    $$('#time_mins a').each(function(el,idx){
      if (el.id == 'minutes_' + mins){
        el.addClassName('active');
      } else {
        el.removeClassName('active');
      }   
    });
    calculateTime();
  }

  function clearTimeSelections(){
    $$('#time_mins a').each(function(el,idx){el.removeClassName('active');});
    $$('#time_hours a').each(function(el,idx){el.removeClassName('active');});
  }

  function calculateTime(){
    hrs = $('hours').value;
    mns = $('minutes').value;
    $('timelog_time').value = hrs + ':' + (mns < 10 ? '0' + mns : mns);
  }

  function updateTimes(val)
  {
    if (/^[0-9]:[0123456][0-9]$/.match(val)){
      parts = val.split(':');
      updateTimeHours(parts[0]);
      updateTimeMinutes(parts[1]);
    }         
  }

"); ?>
<div id="filter">
  <h2>Add Timelog to Workorder #<?php echo $workorder->getId(); ?> Task: "<?php echo $item->getLabel(); ?>"</h2>
</div>

<?php echo form_tag('timelogs/details?id='.$workorder->getId().'&item='.$item->getId()); ?>
<input type="hidden" id="labour_type_id" name="labour_type_id" value="<?php echo $sf_request->getParameter('labour_type_id'); ?>" />
  <input type="hidden" id="hours" name="hours" value="0" />
  <input type="hidden" id="minutes" name="minutes" value="0" />
  <div class="labour-area rounded10">
    <h2 class="rounded10-top">Labour Type</h2>
    <?php foreach ($labours AS $labour): ?>
      <div class="labour-item">
        <?php echo link_to_function($labour->getName(), "updateLabour(".$labour->getId().")", array('id' => 'labour_option_'.$labour->getId(), 'class' => 'button')); ?>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="time-area rounded10">
    <h2 class="rounded10-top">Time Spent</h2>
    <p>Touch the hours and minutes or enter the time taken by touching the text field and using the keyboard to enter a value in h:mm format</p>

    <div style="float: right; margin-right: 15px; padding-top: 23px;">
    <input type="text" style="width: 70px; height: 50px; font-size: 22px; padding-left:15px;" name="timelog_time" id="timelog_time" onkeyup="clearTimeSelections();" value="<?php echo $sf_request->getParameter('timelog_time'); ?>" />
    </div>

      <div class="time-label">Hours:</div>
      <div id="time_hours">
        <?php for ($i = 0; $i <= 8; $i ++): ?>
          <?php echo link_to_function($i, "updateTimeHours(".$i.")", array('class' => 'button', 'id' => 'hours_'.$i)); ?>
        <?php endfor; ?>
      </div>

    <div class="time-label">Minutes:</div>
    <div id="time_mins">
      <?php for ($i = 0; $i <= 45; $i += 15): ?>
        <?php echo link_to_function(':'.($i < 10 ? '0'.$i : $i), "updateTimeMinutes(".$i.")", array('class' => 'button', 'style' => 'margin-right: 10px;', 'id' => 'minutes_'.$i)); ?>
      <?php endfor; ?>
    </div>
    <div class="clear"></div>

  </div>

  <div class="notes-area rounded10">
    <h2 class="rounded10-top">Notes About Tasks Performed</h2>
    <p>Using the keyboard, enter anything of note that might be of future use to the customer.</p>
    <textarea wrap="auto" style="width: 720px; margin: 20px 0 30px 50px; height: 250px; font-size: 14px;" name="notes"><?php echo ($sf_request->getParameter('notes', null)); ?></textarea>
  </div>


  <div class="error-area">
    <?php if ($sf_request->hasParameter('labour_error')): ?>
      <div class="smallwarning error">Please select a labour type.</div>
    <?php elseif ($sf_request->hasParameter('time_error')): ?>
      <div class="smallwarning error">Invalid Time Specified. Try again.</div>
    <?php endif; ?>
  </div>

  <div class="submit-area">
    <button type="submit">Save Timelog</button>
  </div>
</form>

<?php echo javascript_tag("
    updateLabour($('labour_type_id').value);
    updateTimes($('timelog_time').value);
"); ?>
