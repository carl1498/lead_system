!function(e){var t={};function o(a){if(t[a])return t[a].exports;var n=t[a]={i:a,l:!1,exports:{}};return e[a].call(n.exports,n,n.exports,o),n.l=!0,n.exports}o.m=e,o.c=t,o.d=function(e,t,a){o.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:a})},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="/",o(o.s=4)}({4:function(e,t,o){e.exports=o("dzTm")},dzTm:function(e,t){function o(e,t,o){return t in e?Object.defineProperty(e,t,{value:o,enumerable:!0,configurable:!0,writable:!0}):e[t]=o,e}$(document).ready(function(){var e,t,a=$("#type_select").val(),n=$("#book_type_select").val(),s=$("#invoice_select").val(),i="Invoice",r=!0;function c(){$("li.invoice_pick").removeClass("disabled").css("cursor","pointer"),$("a.invoice_pick").removeClass("disabled").css("pointer-events","auto"),$(".refresh_table").attr("disabled",!1)}function l(){switch($("li.invoice_pick").addClass("disabled").css("cursor","not-allowed"),$("a.invoice_pick").addClass("disabled").css("pointer-events","none"),$(".refresh_table").attr("disabled",!0),"Invoice"==i?($(".invoice_select").hide(),$("#invoice_select").next(".select2-container").hide(),$(".book_type_select").hide(),$("#book_type_select").next(".select2-container").hide(),$(".type_select").show(),$("#type_select").next(".select2-container").show()):"Add Book History"==i&&($(".type_select").hide(),$("#type_select").next(".select2-container").hide(),$(".invoice_select").show(),$("#invoice_select").next(".select2-container").show(),$(".book_type_select").show(),$("#book_type_select").next(".select2-container").show()),i){case"Invoice":!function(){a=$("#type_select").val();$("#invoice_table").DataTable({initComplete:function(e,t){c()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:1},responsive:!0,ajax:{url:"/view_invoice/{type_select}",data:{type_select:a}},columns:[{data:"reference_no.invoice_ref_no",name:"invoice_ref_no"},{data:"book_1",name:"book_1"},{data:"wb_1",name:"wb_1"},{data:"book_2",name:"book_2"},{data:"wb_2",name:"wb_2"},{data:"kanji",name:"kanji"},{data:"book_1_ssw",name:"book_1_ssw"},{data:"wb_1_ssw",name:"wb_1_ssw"},{data:"book_2_ssw",name:"book_2_ssw"},{data:"wb_2_ssw",name:"wb_2_ssw"},{data:"kanji_ssw",name:"kanji_ssw"},{data:"created_at",name:"date"},{data:"action",orderable:!1,searchable:!1}],columnDefs:[{defaultContent:"",targets:"_all"}],order:[[11,"desc"]]})}();break;case"Add Book History":!function(){n=$("#book_type_select").val(),s=$("#invoice_select").val();$("#add_books_table").DataTable({initComplete:function(e,t){c()},stateSave:!0,stateSaveCallback:function(e,t){localStorage.setItem("DataTables_"+e.sInstance,JSON.stringify(t))},stateLoadCallback:function(e){return JSON.parse(localStorage.getItem("DataTables_"+e.sInstance))},stateLoadParams:function(e,t){t.order&&delete t.order},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:2},responsive:!0,ajax:{url:"/viewAddBooks",data:{book_type_select:n,invoice_select:s}},columns:[{data:"reference_no.invoice_ref_no",name:"invoice_ref_no"},{data:"book_type.description",name:"book_type"},{data:"previous_pending",name:"previous_pending"},{data:"quantity",name:"quantity"},{data:"pending",name:"pending"},{data:"book_range",name:"book_range"},{data:"created_at",name:"date"},{data:"remarks",name:"remarks"},{data:"action",orderable:!1,searchable:!1}],columnDefs:[{defaultContent:"",targets:"_all"}],order:[[6,"desc"]]})}()}}$("#invoice_modal").on("hidden.bs.modal",function(e){$("#invoice_form :input.required").each(function(){this.style.setProperty("border-color","green","important")}),$(this).find("input,textarea,select").val("").end(),r=!0}),$("#add_books_modal").on("hidden.bs.modal",function(e){$(this).find("input,textarea,select").val("").end().trigger("change"),$("#invoice_add_book").val("").trigger("change"),$("#book_type_add_book").val("").trigger("change"),$("#book_type_add_book, #quantity, #starting, #remarks").prop("disabled",!0),r=!0}),$(".select2").select2(),$("#invoice_add_book").select2({placeholder:"Select Invoice",ajax:{url:"/invoiceAll",dataType:"json",data:function(e){return{name:e.term,page:e.page}},processResults:function(e){return{results:e.results}}}}),$("input, select").attr("autocomplete","off"),$("body").tooltip({selector:'[data-toggle="tooltip"]',trigger:"hover"}),$('[data-toggle="tooltip"]').click(function(){$('[data-toggle="tooltip"]').tooltip("hide")}),$(".refresh_table").on("click",function(){l()}),$(document).on("shown.bs.tab",'a[data-toggle="tab"]',function(e){1==r&&(l(),$.fn.dataTable.tables({visible:!0,api:!0}).columns.adjust())}),l(),$(".invoice_pick").on("click",function(){$(this).hasClass("disabled")||(i=$(this).text())}),$(document).on("change","#invoice_add_book",function(){var t;e=$("#invoice_add_book").val(),$("#book_type_add_book").val("").trigger("change"),$("#book_type_add_book").prop("disabled",!1),$("#quantity, #starting, #remarks").prop("disabled",!0),$("#quantity, #starting, #start, #pending, #end, #remarks").val(""),$("#book_type_add_book").select2({placeholder:"Select Book",ajax:(t={url:"/bookAll/"+e,data:{invoice_id:e},dataType:"json"},o(t,"data",function(e){return{name:e.term,page:e.page}}),o(t,"processResults",function(e){return{results:e.results}}),t)})}),$(document).on("submit","#add_books_form",function(e){e.preventDefault();var t=$(".save_books"),o=document.getElementsByClassName("save_books")[0];o.disabled=!0,t.html("SAVING..."),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_books",method:"POST",data:$(this).serialize(),success:function(e){notif("Success!","Record has been saved to the Database!","success","glyphicon-ok"),$("#add_books_modal").modal("hide"),o.disabled=!1,t.html("SAVE CHANGES"),l()},error:function(e){swal("Error!","Something went wrong, try again.","error"),o.disabled=!1,t.html("SAVE CHANGES")}})}),$(document).on("change","#book_type_add_book",function(o){o.preventDefault(),$("#book_type_add_book").val()?($("#quantity, #starting, #start, #pending, #end, #remarks").val(""),$("#quantity").prop("disabled",!1),$("#remarks").prop("disabled",!1),e=$("#invoice_add_book").val(),t=$("#book_type_add_book").val(),$.ajax({url:"/getPending",method:"get",data:{invoice_id:e,book_type:t},dataType:"text",success:function(e){$("#previous_pending").val(e),$("#pending").val(e)}}),$.ajax({url:"/getStarting/"+t,method:"get",data:{book_type:t},dataType:"text",success:function(e){$("#starting").val(e),$("#start").val(e),$("#starting").prop("disabled",!1)}})):$("#previous_pending").val("")}),$("#starting").keyup(function(){$("#start").val($("#starting").val()),$("#end").val(parseInt($("#start").val())+(parseInt($("#quantity").val())-1))}),$("#quantity").keyup(function(){return""==$("#quantity").val()?($("#pending").val($("#previous_pending").val()),void $("#end").val("")):$("#previous_pending").val()-$("#quantity").val()<0?($("#quantity").val($("#previous_pending").val()),$("#pending").val($("#previous_pending").val()-$("#quantity").val()),void $("#end").val(parseInt($("#start").val())+(parseInt($("#quantity").val())-1))):($("#pending").val($("#previous_pending").val()-$("#quantity").val()),void $("#end").val(parseInt($("#start").val())+(parseInt($("#quantity").val())-1)))}),$(document).on("change","#type_select",function(){l()}),$(".add_invoice").on("click",function(){r=!1,$("#invoice_modal").modal("toggle"),$("#invoice_modal").modal("show")}),$(".add_books").on("click",function(){r=!1,$("#add_books_modal").modal("toggle"),$("#add_books_modal").modal("show")}),$(document).on("submit","#invoice_form",function(e){e.preventDefault();var t=$(".save_invoice"),o=document.getElementsByClassName("save_invoice")[0];o.disabled=!0,t.html("SAVING..."),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_invoice",method:"POST",data:$(this).serialize(),success:function(e){if("false"==e)return swal("Duplicate!","Invoice reference no. already existing","error"),o.disabled=!1,void t.html("SAVE CHANGES");notif("Success!","Record has been saved to the Database!","success","glyphicon-ok"),$("#invoice_modal").modal("hide"),o.disabled=!1,t.html("SAVE CHANGES"),l()},error:function(e){swal("Error!","Something went wrong, try again.","error"),o.disabled=!1,t.html("SAVE CHANGES")}})}),$(document).on("click",".delete_invoice",function(){var e=$(this).attr("id");swal.fire({title:"Confirm User",text:"For security purposes, input your password again.",input:"password",inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Confirm",showLoaderOnConfirm:!0,preConfirm:function(t){$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/confirm_user",data:{password:t},method:"POST",success:function(o){0!=o?swal({title:"Warning",text:"This may delete data of books added for this invoice.",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then(function(o){o.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/delete_invoice/"+e,data:{password:t},method:"get",type:"json",success:function(e){notif("Success!","This invoice has been Deleted","success","glyphicon-ok"),l()}})}):swal("Password Incorrect!","Please try again","error")}})}})}),$(document).on("change","#book_type_select",function(){l()}),$(document).on("change","#invoice_select",function(){l()}),$(document).on("click",".delete_add_book",function(){var e=$(this).attr("id");swal.fire({title:"Confirm User",text:"For security purposes, input your password again.",input:"password",inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Confirm",showLoaderOnConfirm:!0,preConfirm:function(t){$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/confirm_user",data:{password:t},method:"POST",success:function(o){0!=o?swal({title:"Warning",text:"This may delete data of books.",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then(function(o){o.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/delete_add_book/"+e,data:{password:t},method:"get",type:"json",success:function(e){1!=e?(notif("Success!","This add book history has been Deleted","success","glyphicon-ok"),l()):swal("Error!","Some books within range are already released or not available","error")}})}):swal("Password Incorrect!","Please try again","error")}})}})})})}});