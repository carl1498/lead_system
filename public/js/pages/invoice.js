!function(e){var t={};function a(o){if(t[o])return t[o].exports;var n=t[o]={i:o,l:!1,exports:{}};return e[o].call(n.exports,n,n.exports,a),n.l=!0,n.exports}a.m=e,a.c=t,a.d=function(e,t,o){a.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:o})},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="/",a(a.s=4)}({4:function(e,t,a){e.exports=a("dzTm")},dzTm:function(e,t){function a(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}$(document).ready(function(){var e,t,o=$("#type_select").val(),n=$("#book_type_select").val(),i=$("#invoice_select").val(),s="Invoice",r=!0;function d(){$("li.invoice_pick").removeClass("disabled").css("cursor","pointer"),$("a.invoice_pick").removeClass("disabled").css("pointer-events","auto"),$(".refresh_table").attr("disabled",!1)}function c(){switch($("li.invoice_pick").addClass("disabled").css("cursor","not-allowed"),$("a.invoice_pick").addClass("disabled").css("pointer-events","none"),$(".refresh_table").attr("disabled",!0),"Invoice"==s?($(".invoice_select").hide(),$("#invoice_select").next(".select2-container").hide(),$(".book_type_select").hide(),$("#book_type_select").next(".select2-container").hide(),$(".type_select").show(),$("#type_select").next(".select2-container").show()):"Add Book History"==s&&($(".type_select").hide(),$("#type_select").next(".select2-container").hide(),$(".invoice_select").show(),$("#invoice_select").next(".select2-container").show(),$(".book_type_select").show(),$("#book_type_select").next(".select2-container").show()),s){case"Invoice":!function(){o=$("#type_select").val();$("#invoice_table").DataTable({initComplete:function(e,t){d()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:1},responsive:!0,ajax:{url:"/view_invoice/{type_select}",data:{type_select:o}},columns:[{data:"reference_no.invoice_ref_no",name:"invoice_ref_no"},{data:"book_1",name:"book_1"},{data:"wb_1",name:"wb_1"},{data:"book_2",name:"book_2"},{data:"wb_2",name:"wb_2"},{data:"kanji",name:"kanji"},{data:"book_1_ssv",name:"book_1_ssv"},{data:"wb_1_ssv",name:"wb_1_ssv"},{data:"book_2_ssv",name:"book_2_ssv"},{data:"wb_2_ssv",name:"wb_2_ssv"},{data:"kanji_ssv",name:"kanji_ssv"},{data:"created_at",name:"date"},{data:"action",orderable:!1,searchable:!1}],columnDefs:[{width:120,targets:0},{width:60,targets:1},{width:60,targets:2},{width:60,targets:3},{width:60,targets:4},{width:60,targets:5},{width:90,targets:6},{width:90,targets:7},{width:90,targets:8},{width:90,targets:9},{width:90,targets:10},{width:130,targets:11},{width:80,targets:12}],order:[[11,"desc"]]})}();break;case"Add Book History":!function(){n=$("#book_type_select").val(),i=$("#invoice_select").val();$("#add_books_table").DataTable({initComplete:function(e,t){d()},stateSave:!0,stateSaveCallback:function(e,t){localStorage.setItem("DataTables_"+e.sInstance,JSON.stringify(t))},stateLoadCallback:function(e){return JSON.parse(localStorage.getItem("DataTables_"+e.sInstance))},stateLoadParams:function(e,t){t.order&&delete t.order},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:2},responsive:!0,ajax:{url:"/viewAddBooks",data:{book_type_select:n,invoice_select:i}},columns:[{data:"reference_no.invoice_ref_no",name:"invoice_ref_no"},{data:"book_type.description",name:"book_type"},{data:"previous_pending",name:"previous_pending"},{data:"quantity",name:"quantity"},{data:"pending",name:"pending"},{data:"book_range",name:"book_range"},{data:"created_at",name:"date"},{data:"remarks",name:"remarks"},{data:"action",orderable:!1,searchable:!1}],columnDefs:[{width:120,targets:0},{width:250,targets:1},{width:60,targets:2},{width:60,targets:3},{width:60,targets:4},{width:120,targets:5},{width:130,targets:6},{width:200,targets:7},{width:80,targets:8}],order:[[6,"desc"]]})}()}}$("#invoice_modal").on("hidden.bs.modal",function(e){$("#invoice_form :input.required").each(function(){this.style.setProperty("border-color","green","important")}),$(this).find("input,textarea,select").val("").end(),r=!0}),$("#add_books_modal").on("hidden.bs.modal",function(e){$(this).find("input,textarea,select").val("").end().trigger("change"),$("#invoice_add_book").val("").trigger("change"),$("#book_type_add_book").val("").trigger("change"),$("#book_type_add_book, #quantity, #starting, #remarks").prop("disabled",!0),r=!0}),$(".select2").select2(),$("#invoice_add_book").select2({placeholder:"Select Invoice",ajax:{url:"/invoiceAll",dataType:"json",data:function(e){return{name:e.term,page:e.page}},processResults:function(e){return{results:e.results}}}}),$("input, select").attr("autocomplete","off"),$("body").tooltip({selector:'[data-toggle="tooltip"]',trigger:"hover"}),$('[data-toggle="tooltip"]').click(function(){$('[data-toggle="tooltip"]').tooltip("hide")}),$(".refresh_table").on("click",function(){c()}),$(document).on("shown.bs.tab",'a[data-toggle="tab"]',function(e){1==r&&(c(),$.fn.dataTable.tables({visible:!0,api:!0}).columns.adjust())}),c(),$(".invoice_pick").on("click",function(){$(this).hasClass("disabled")||(s=$(this).text())}),$(document).on("change","#invoice_add_book",function(){var t;e=$("#invoice_add_book").val(),$("#book_type_add_book").val("").trigger("change"),$("#book_type_add_book").prop("disabled",!1),$("#quantity, #starting, #remarks").prop("disabled",!0),$("#quantity, #starting, #start, #pending, #end, #remarks").val(""),$("#book_type_add_book").select2({placeholder:"Select Book",ajax:(t={url:"/bookAll/"+e,data:{invoice_id:e},dataType:"json"},a(t,"data",function(e){return{name:e.term,page:e.page}}),a(t,"processResults",function(e){return{results:e.results}}),t)})}),$(document).on("submit","#add_books_form",function(e){e.preventDefault();var t=$(".save_books"),a=document.getElementsByClassName("save_books")[0];a.disabled=!0,t.html("SAVING..."),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_books",method:"POST",data:$(this).serialize(),success:function(e){notif("Success!","Record has been saved to the Database!","success","glyphicon-ok"),$("#add_books_modal").modal("hide"),a.disabled=!1,t.html("SAVE CHANGES"),c()},error:function(e){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,t.html("SAVE CHANGES")}})}),$(document).on("change","#book_type_add_book",function(a){a.preventDefault(),$("#book_type_add_book").val()?($("#quantity, #starting, #start, #pending, #end, #remarks").val(""),$("#quantity").prop("disabled",!1),$("#remarks").prop("disabled",!1),e=$("#invoice_add_book").val(),t=$("#book_type_add_book").val(),$.ajax({url:"/getPending/"+e+"/"+t,method:"get",data:{invoice_id:e,book_type:t},dataType:"text",success:function(e){$("#previous_pending").val(e),$("#pending").val(e)}}),$.ajax({url:"/getStarting/"+t,method:"get",data:{book_type:t},dataType:"text",success:function(e){$("#starting").val(e),$("#start").val(e),$("#starting").prop("disabled",!1)}})):$("#previous_pending").val("")}),$("#starting").keyup(function(){$("#start").val($("#starting").val()),$("#end").val(parseInt($("#start").val())+(parseInt($("#quantity").val())-1))}),$("#quantity").keyup(function(){return""==$("#quantity").val()?($("#pending").val($("#previous_pending").val()),void $("#end").val("")):$("#previous_pending").val()-$("#quantity").val()<0?($("#quantity").val($("#previous_pending").val()),$("#pending").val($("#previous_pending").val()-$("#quantity").val()),void $("#end").val(parseInt($("#start").val())+(parseInt($("#quantity").val())-1))):($("#pending").val($("#previous_pending").val()-$("#quantity").val()),void $("#end").val(parseInt($("#start").val())+(parseInt($("#quantity").val())-1)))}),$(document).on("change","#type_select",function(){c()}),$(".add_invoice").on("click",function(){r=!1,$("#invoice_modal").modal("toggle"),$("#invoice_modal").modal("show")}),$(".add_books").on("click",function(){r=!1,$("#add_books_modal").modal("toggle"),$("#add_books_modal").modal("show")}),$(document).on("submit","#invoice_form",function(e){e.preventDefault();var t=$(".save_invoice"),a=document.getElementsByClassName("save_invoice")[0];a.disabled=!0,t.html("SAVING..."),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_invoice",method:"POST",data:$(this).serialize(),success:function(e){if("false"==e)return swal("Duplicate!","Invoice reference no. already existing","error"),a.disabled=!1,void t.html("SAVE CHANGES");notif("Success!","Record has been saved to the Database!","success","glyphicon-ok"),$("#invoice_modal").modal("hide"),a.disabled=!1,t.html("SAVE CHANGES"),c()},error:function(e){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,t.html("SAVE CHANGES")}})}),$(document).on("click",".delete_invoice",function(){var e=$(this).attr("id");swal.fire({title:"Confirm User",text:"For security purposes, input your password again.",input:"password",inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Confirm",showLoaderOnConfirm:!0,preConfirm:function(t){$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/confirm_user",data:{password:t},method:"POST",success:function(t){0!=t?swal({title:"Warning",text:"This may delete data of books added for this invoice.",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/delete_invoice/"+e,method:"get",type:"json",success:function(e){notif("Success!","This invoice has been Deleted","success","glyphicon-ok"),c()}})}):swal("Password Incorrect!","Please try again","error")}})}})}),$(document).on("change","#book_type_select",function(){c()}),$(document).on("change","#invoice_select",function(){c()}),$(document).on("click",".delete_add_book",function(){var e=$(this).attr("id");swal.fire({title:"Confirm User",text:"For security purposes, input your password again.",input:"password",inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Confirm",showLoaderOnConfirm:!0,preConfirm:function(t){$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/confirm_user",data:{password:t},method:"POST",success:function(t){0!=t?swal({title:"Warning",text:"This may delete data of books.",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/delete_add_book/"+e,method:"get",type:"json",success:function(e){1!=e?(notif("Success!","This add book history has been Deleted","success","glyphicon-ok"),c()):swal("Error!","Some books within range are already released or not available","error")}})}):swal("Password Incorrect!","Please try again","error")}})}})})})}});