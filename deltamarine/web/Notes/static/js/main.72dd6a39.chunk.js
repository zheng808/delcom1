(this.webpackJsonpfrontend=this.webpackJsonpfrontend||[]).push([[0],{23:function(e,t,n){},39:function(e,t,n){},68:function(e,t,n){"use strict";n.r(t);var c=n(1),a=n.n(c),r=n(32),s=n.n(r),o=(n(39),n(11)),i=n(2),l=(n(23),n(4)),u=n.n(l),j=n(10),d=n(5),b=n(0);var h=function(){return Object(b.jsx)("p",{className:"error-message",children:"That username/password is incorrect"})},p=n(17),O=n(20);function f(e){var t=e.setToken,n=function(e){var t=Object(c.useState)(e),n=Object(d.a)(t,2),a=n[0],r=n[1];return[a,function(e){r(Object(O.a)(Object(O.a)({},a),{},Object(p.a)({},e.target.name,e.target.value)))}]}({user:"",password:""}),a=Object(d.a)(n,2),r=a[0],s=a[1],o=Object(c.useState)(!1),i=Object(d.a)(o,2),l=i[0],f=i[1];function x(e){return m.apply(this,arguments)}function m(){return(m=Object(j.a)(u.a.mark((function e(t){var n;return u.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return null!=(n=t.user)&&sessionStorage.setItem("userName",n),e.abrupt("return",fetch("/auth/login",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(r)}).then((function(e){return 401===e.status&&f({error:!0}),e.json()})).catch((function(e){return console.error(e)})));case 3:case"end":return e.stop()}}),e)})))).apply(this,arguments)}var v=function(){var e=Object(j.a)(u.a.mark((function e(n){var c;return u.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n.preventDefault(),e.prev=1,e.next=4,x(r);case 4:c=e.sent,t(c),e.next=11;break;case 8:e.prev=8,e.t0=e.catch(1),alert(e.t0.message);case 11:case"end":return e.stop()}}),e,null,[[1,8]])})));return function(t){return e.apply(this,arguments)}}();return Object(b.jsx)("div",{className:"forms-container",children:Object(b.jsx)("div",{className:"signin-signup",children:Object(b.jsxs)("form",{onSubmit:v,className:"sign-in-form",children:[Object(b.jsx)("h2",{className:"title",children:"Sign in"}),Object(b.jsxs)("div",{className:"input-field",children:[Object(b.jsx)("i",{className:"fas fa-user"}),Object(b.jsx)("input",{type:"text",placeholder:"username",name:"user",onChange:s})]}),Object(b.jsxs)("div",{className:"input-field",children:[Object(b.jsx)("i",{className:"fas fa-lock"}),Object(b.jsx)("input",{type:"password",placeholder:"password",name:"password",onChange:s})]}),Object(b.jsx)("div",{className:"login-button",children:Object(b.jsx)("input",{type:"submit",value:"Login",className:"btn-login"})}),l&&Object(b.jsx)(h,{})]})})})}var x=function(){var e=Object(c.useState)([]),t=Object(d.a)(e,2),n=t[0],a=t[1],r=Object(c.useState)(""),s=Object(d.a)(r,2),l=s[0],h=s[1],p=Object(c.useState)([]),O=Object(d.a)(p,2),f=O[0],x=O[1],m=Object(c.useState)([]),v=Object(d.a)(m,2),N=v[0],g=v[1],k=Object(i.f)(),w=function(e){k.push("/task/".concat(e.id))};return Object(c.useEffect)((function(){(function(){var e=Object(j.a)(u.a.mark((function e(){var t,n;return u.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,fetch("/api/workorder");case 3:return t=e.sent,e.next=6,t.json();case 6:n=e.sent,g(n),a(n),e.next=14;break;case 11:e.prev=11,e.t0=e.catch(0),console.log(e.t0);case 14:case"end":return e.stop()}}),e,null,[[0,11]])})));return function(){return e.apply(this,arguments)}})()()}),[]),Object(c.useEffect)((function(){var e=N.filter((function(e){return e.id.toString()===l?e:null}));x(e)}),[N,l]),Object(b.jsx)(o.a,{children:Object(b.jsxs)("div",{className:"container",children:[Object(b.jsx)("div",{className:"container-header",children:Object(b.jsx)("input",{className:"form-control",type:"text",placeholder:"Search WorkOrder",value:l,onChange:function(e){h(e.target.value)}})}),Object(b.jsxs)("table",{className:"table table-hover table-bordered",children:[Object(b.jsx)("thead",{children:Object(b.jsxs)("tr",{className:"table-primary",children:[Object(b.jsx)("th",{scope:"col",children:"WorkOrder ID"}),Object(b.jsx)("th",{scope:"col",children:"BoatName"}),Object(b.jsx)("th",{scope:"col",children:"Customer Name"})]})}),Object(b.jsx)("tbody",{children:0===f.length?n.map((function(e){return Object(b.jsxs)("tr",{onClick:function(){return w(e)},children:[Object(b.jsx)("td",{children:e.id}),Object(b.jsx)("td",{children:e.name}),Object(b.jsx)("td",{children:e.alpha_name})]},e.id)})):f.map((function(e){return Object(b.jsxs)("tr",{onClick:function(){return w(e)},children:[Object(b.jsx)("td",{children:e.id}),Object(b.jsx)("td",{children:e.name}),Object(b.jsx)("td",{children:e.alpha_name})]},e.id)}))})]})]})})},m=n(15),v=n.n(m);var N=function(){return Object(b.jsx)(o.b,{to:"/workorder",children:Object(b.jsx)("i",{className:"fas fa-home home-icon"})})};var g=function(){var e=Object(i.f)();return Object(b.jsx)("i",{className:"fas fa-arrow-circle-left left-button",onClick:function(){return e.goBack()}})};var k=function(e){var t=e.match,n=Object(c.useState)([]),a=Object(d.a)(n,2),r=a[0],s=a[1],l=Object(i.f)();return Object(c.useEffect)((function(){(function(){var e=Object(j.a)(u.a.mark((function e(){var n,c,a;return u.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,n=t.params.id,e.next=4,v.a.post("/api/task/".concat(n),{workId:n}).then((function(e){return e}),(function(e){console.log(e)}));case 4:return c=e.sent,e.next=7,c.data;case 7:a=e.sent,s(a),e.next=14;break;case 11:e.prev=11,e.t0=e.catch(0),console.log(e.t0);case 14:case"end":return e.stop()}}),e,null,[[0,11]])})));return function(){return e.apply(this,arguments)}})()()}),[t.params.id]),Object(b.jsx)(o.a,{forceRefresh:!0,children:Object(b.jsxs)("div",{className:"container",children:[Object(b.jsxs)("div",{className:"container-header",children:[Object(b.jsx)(N,{}),Object(b.jsx)(g,{})]}),Object(b.jsxs)("table",{className:"table table-hover table-bordered",children:[Object(b.jsx)("thead",{children:Object(b.jsxs)("tr",{className:"table-primary",children:[Object(b.jsx)("th",{scope:"col",children:"TaskID"}),Object(b.jsx)("th",{scope:"col",children:"Label"})]})}),Object(b.jsx)("tbody",{children:r.map((function(e,t){return Object(b.jsxs)("tr",{onClick:function(){return function(e){l.push("/notes/".concat(e.id))}(e)},children:[Object(b.jsx)("td",{children:t}),Object(b.jsx)("td",{children:e.label})]},e.id)}))})]})]})})};var w=n(34);var S=function(e){var t=e.match,n=Object(c.useState)([]),a=Object(d.a)(n,2),r=a[0],s=a[1],o=Object(c.useState)(""),i=Object(d.a)(o,2),l=i[0],h=i[1],p=function(){var e=Object(j.a)(u.a.mark((function e(){var n,c,a;return u.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,n=t.params.id,c=sessionStorage.getItem("userName"),a=(new Date).toISOString().slice(0,19).replace("Z","").replace("T"," "),e.next=6,v.a.post("/api/notes/saveNotes",{text:l,created_time:a,owner:c,task_id:n}).then((function(e){200!==e.status&&console.log("bad request"),s(Object(w.a)(e.data))})).catch((function(e){return console.log(e)}));case 6:e.next=11;break;case 8:e.prev=8,e.t0=e.catch(0),alert(e.t0.message);case 11:case"end":return e.stop()}}),e,null,[[0,8]])})));return function(){return e.apply(this,arguments)}}(),O=function(){var e=Object(j.a)(u.a.mark((function e(t){return u.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t.preventDefault(),p();case 2:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}();return Object(c.useEffect)((function(){(function(){var e=Object(j.a)(u.a.mark((function e(){var n,c,a;return u.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,n=t.params.id,e.next=4,v.a.post("/api/notes/".concat(n),{task_id:n}).then((function(e){return e}),(function(e){console.log(e)}));case 4:return c=e.sent,e.next=7,c.data;case 7:a=e.sent,s(a),e.next=14;break;case 11:e.prev=11,e.t0=e.catch(0),console.log(e.t0);case 14:case"end":return e.stop()}}),e,null,[[0,11]])})));return function(){return e.apply(this,arguments)}})()()}),[t.params.id]),Object(b.jsxs)("div",{className:"container",children:[Object(b.jsxs)("div",{className:"container-header",children:[Object(b.jsx)(N,{}),Object(b.jsx)(g,{})]}),Object(b.jsx)("div",{className:"row",children:Object(b.jsx)("form",{onSubmit:O,className:"write-note-section",children:Object(b.jsxs)("div",{className:"form-group col-sm-12",children:[Object(b.jsx)("textarea",{className:"form-control",id:"notes-editor",name:"notes",onChange:function(e){var t=e.target.value;h(t)}}),Object(b.jsx)("div",{className:"save-button-wrapper",children:Object(b.jsx)("button",{type:"submit",className:"btn btn-primary save_note",children:"Save Note"})})]})})}),Object(b.jsx)("div",{className:"row",children:Object(b.jsx)("div",{className:"notes-section",children:r.map((function(e){return Object(b.jsxs)("div",{className:"detail-section col-sm-12",children:[Object(b.jsxs)("p",{children:[new Date(e.date_created).toLocaleString()," created by ",e.owner]}),Object(b.jsx)("textarea",{disabled:!0,defaultValue:e.text,className:"note-textarea ",row:3})]},e.id)}))})})]})},y=function(){var e=function(){var e=Object(c.useState)(function(){var e=sessionStorage.getItem("token"),t=JSON.parse(e);return null===t||void 0===t?void 0:t.token}()),t=Object(d.a)(e,2),n=t[0],a=t[1];return{setToken:function(e){sessionStorage.setItem("token",JSON.stringify(e)),a(e.token)},token:n}}(),t=e.token,n=e.setToken;return t?Object(b.jsx)(o.a,{children:Object(b.jsx)("div",{className:"App",children:Object(b.jsx)("div",{className:"container-fluid",children:Object(b.jsxs)(i.c,{children:[Object(b.jsx)(i.a,{path:"/workorder",children:Object(b.jsx)(x,{})}),Object(b.jsx)(i.a,{path:"/task/:id",component:k}),Object(b.jsx)(i.a,{path:"/notes/:id",component:S})]})})})}):Object(b.jsx)("div",{className:"App",children:Object(b.jsx)("header",{className:"App-header",children:Object(b.jsx)(f,{setToken:n})})})},C=function(e){e&&e instanceof Function&&n.e(3).then(n.bind(null,69)).then((function(t){var n=t.getCLS,c=t.getFID,a=t.getFCP,r=t.getLCP,s=t.getTTFB;n(e),c(e),a(e),r(e),s(e)}))};s.a.render(Object(b.jsx)(a.a.StrictMode,{children:Object(b.jsx)(y,{})}),document.getElementById("root")),C()}},[[68,1,2]]]);
//# sourceMappingURL=main.72dd6a39.chunk.js.map