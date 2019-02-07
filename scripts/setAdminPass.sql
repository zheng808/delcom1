select * from sf_guard_user where username = 'admin';


update sf_guard_user 
   set salt = '886c94c3f4bf84074f1186c0a52b82c6',
       password = '95f1654a84f2df0338ddc630e6050f5d0ab954a8'
 where username = 'admin';