!function(t){var e={};function a(n){if(e[n])return e[n].exports;var r=e[n]={i:n,l:!1,exports:{}};return t[n].call(r.exports,r,r.exports,a),r.l=!0,r.exports}a.m=t,a.c=e,a.d=function(t,e,n){a.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:n})},a.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return a.d(e,"a",e),e},a.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},a.p="/",a(a.s=5)}({5:function(t,e,a){t.exports=a("gG1H")},gG1H:function(t,e){$(document).ready(function(){function t(){$("#student_add_history").DataTable({destroy:!0,stateSave:!0,stateSaveCallback:function(t,e){localStorage.setItem("DataTables_"+t.sInstance,JSON.stringify(e))},stateLoadCallback:function(t){return JSON.parse(localStorage.getItem("DataTables_"+t.sInstance))},stateLoadParams:function(t,e){e.order&&delete e.order},ajax:"/student_add_history_table",columns:[{data:"stud_id",name:"student"},{data:"student.program.name",name:"program"},{data:"type",name:"type"},{data:"added_by",name:"added_by"},{data:"created_at",name:"created_at"}],columnDefs:[{defaultContent:"",targets:"_all"}],order:[[4,"desc"]]})}t(),setInterval(function(){t()},3e5),$(document).on("click",".refresh_table",function(){t()})})}});