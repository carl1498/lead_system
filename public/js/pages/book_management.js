!function(e){var t={};function a(s){if(t[s])return t[s].exports;var o=t[s]={i:s,l:!1,exports:{}};return e[s].call(o.exports,o,o.exports,a),o.l=!0,o.exports}a.m=e,a.c=t,a.d=function(e,t,s){a.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:s})},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="/",a(a.s=1)}({1:function(e,t,a){e.exports=a("a5Lb")},a5Lb:function(e,t){function a(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}$(document).ready(function(){var e,t,s,o="Branch",n=$("#status_select").val(),r=$("#book_type_select").val(),l=$("#student_status_select").val(),i=$("#program_select").val(),c=$("#branch_select").val(),d=$("#invoice_select").val(),u=!0;function _(){$("li.books_pick").removeClass("disabled").css("cursor","pointer"),$("a.books_pick").removeClass("disabled").css("pointer-events","auto"),$(".refresh_table").attr("disabled",!1)}function m(){switch($("li.books_pick").addClass("disabled").css("cursor","not-allowed"),$("a.books_pick").addClass("disabled").css("pointer-events","none"),$(".refresh_table").attr("disabled",!0),"Branch"!=o&&"Student"!=o&&"SSV"!=o?($(".book_type_select").show(),$("#book_type_select").next(".select2-container").show()):($(".book_type_select").hide(),$("#book_type_select").next(".select2-container").hide()),"Branch"!=o&&($(".branch_select").show(),$("#branch_select").next(".select2-container").show(),$(".status_select").hide(),$("#status_select").next(".select2-container").hide()),"Student"!=o&&"SSV"!=o&&"Request History"!=o&&"Release History"!=o?($(".invoice_select").show(),$("#invoice_select").next(".select2-container").show()):($(".invoice_select").hide(),$("#invoice_select").next(".select2-container").hide()),"Student"!=o&&o!=o!="SSV"&&($(".student_status_select").hide(),$("#student_status_select").next(".select2-container").hide(),$(".program_select").hide(),$("#program_select").next(".select2-container").hide()),o){case"Branch":!function(){$(".status_select").show(),$("#status_select").next(".select2-container").show(),$(".branch_select").hide(),$("#branch_select").next(".select2-container").hide();$("#books_branch_table").DataTable({stateSave:!0,stateSaveCallback:function(e,t){localStorage.setItem("DataTables_"+e.sInstance,JSON.stringify(t))},stateLoadCallback:function(e){return JSON.parse(localStorage.getItem("DataTables_"+e.sInstance))},stateLoadParams:function(e,t){t.order&&delete t.order},initComplete:function(e,t){_()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:1},responsive:!0,ajax:{url:"/view_branch_books",data:{book_status:n,invoice_select:d}},columns:[{data:"name",name:"branch"},{data:"book_1",name:"book_1"},{data:"wb_1",name:"wb_1"},{data:"book_2",name:"book_2"},{data:"wb_2",name:"wb_2"},{data:"kanji",name:"kanji"},{data:"book_1_ssv",name:"book_1_ssv"},{data:"wb_1_ssv",name:"wb_1_ssv"},{data:"book_2_ssv",name:"book_2_ssv"},{data:"wb_2_ssv",name:"wb_2_ssv"},{data:"kanji_ssv",name:"kanji_ssv"}],columnDefs:[{width:70,targets:0},{width:90,targets:1},{width:90,targets:2},{width:90,targets:3},{width:90,targets:4},{width:90,targets:5},{width:90,targets:6},{width:90,targets:7},{width:90,targets:8},{width:90,targets:9},{width:90,targets:10}]})}();break;case"Student":!function(){$(".student_status_select").show(),$("#student_status_select").next(".select2-container").show(),$(".program_select").show(),$("#program_select").next(".select2-container").show();$("#books_student_table").DataTable({stateSave:!0,stateSaveCallback:function(e,t){localStorage.setItem("DataTables_"+e.sInstance,JSON.stringify(t))},stateLoadCallback:function(e){return JSON.parse(localStorage.getItem("DataTables_"+e.sInstance))},stateLoadParams:function(e,t){t.order&&delete t.order},initComplete:function(e,t){_()},dom:"Bflrtip",processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:1},buttons:[{extend:"print",title:"LEAD System",orientation:"landscape",pageSize:"FOLIO"},{extend:"pdfHtml5",title:"LEAD System",orientation:"portrait",pageSize:"FOLIO"}],responsive:!0,ajax:{url:"/view_student_books",data:{student_status_select:l,program_select:i,branch_select:c}},columns:[{data:"student_name",name:"student_name"},{data:"branch.name",name:"branch"},{data:"book_1",name:"book_1"},{data:"wb_1",name:"wb_1"},{data:"book_2",name:"book_2"},{data:"wb_2",name:"wb_2"},{data:"kanji",name:"kanji"},{data:"program.name",name:"program",defaultContent:""},{data:"status",name:"status"},{data:"departure",name:"departure"}],columnDefs:[{width:250,targets:0},{width:100,targets:1},{width:60,targets:2},{width:60,targets:3},{width:60,targets:4},{width:60,targets:5},{width:60,targets:6},{width:150,targets:7},{width:110,targets:8},{width:110,targets:9}],order:[[7,"asc"]]})}();break;case"SSV":!function(){$(".student_status_select").show(),$("#student_status_select").next(".select2-container").show(),$(".program_select").show(),$("#program_select").next(".select2-container").show();$("#books_ssv_student_table").DataTable({stateSave:!0,stateSaveCallback:function(e,t){localStorage.setItem("DataTables_"+e.sInstance,JSON.stringify(t))},stateLoadCallback:function(e){return JSON.parse(localStorage.getItem("DataTables_"+e.sInstance))},stateLoadParams:function(e,t){t.order&&delete t.order},initComplete:function(e,t){_()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:1},responsive:!0,ajax:{url:"/view_ssv_student_books",data:{student_status_select:l,program_select:i,branch_select:c}},columns:[{data:"student_name",name:"student_name"},{data:"branch.name",name:"branch"},{data:"book_1_ssv",name:"book_1_ssv"},{data:"wb_1_ssv",name:"wb_1_ssv"},{data:"book_2_ssv",name:"book_2_ssv"},{data:"wb_2_ssv",name:"wb_2_ssv"},{data:"kanji_ssv",name:"kanji_ssv"},{data:"program.name",name:"program",defaultContent:""},{data:"status",name:"status"}],columnDefs:[{width:250,targets:0},{width:100,targets:1},{width:60,targets:2},{width:60,targets:3},{width:60,targets:4},{width:60,targets:5},{width:60,targets:6},{width:150,targets:7},{width:110,targets:8}]})}();break;case"Books":$("#books_table").DataTable((a(e={stateSave:!0,stateSaveCallback:function(e,t){localStorage.setItem("DataTables_"+e.sInstance,JSON.stringify(t))},stateLoadCallback:function(e){return JSON.parse(localStorage.getItem("DataTables_"+e.sInstance))},stateLoadParams:function(e,t){t.order&&delete t.order},initComplete:function(e,t){_()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:3},responsive:!0,ajax:"/view_books/"+r},"ajax",{url:"/view_books",data:{book_type_select:r,branch_select:c,invoice_select:d}}),a(e,"columns",[{data:"book_type.description",name:"book_type"},{data:"name",name:"book_no"},{data:"reference_no.invoice_ref_no",name:"invoice_ref_no"},{data:"branch.name",name:"branch"},{data:"status",name:"status"},{data:"student_name",name:"student_name"},{data:"action",orderable:!1,searchable:!1}]),a(e,"columnDefs",[{width:250,targets:0},{width:80,targets:1},{width:130,targets:2},{width:100,targets:3},{width:100,targets:4},{width:250,targets:5},{width:100,targets:6}]),e));break;case"Request History":$("#books_request_table").DataTable({stateSave:!0,stateSaveCallback:function(e,t){localStorage.setItem("DataTables_"+e.sInstance,JSON.stringify(t))},stateLoadCallback:function(e){return JSON.parse(localStorage.getItem("DataTables_"+e.sInstance))},stateLoadParams:function(e,t){t.order&&delete t.order},initComplete:function(e,t){_()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:3},responsive:!0,ajax:{url:"/view_request_books",data:{book_type_select:r,branch_select:c}},columns:[{data:"id",name:"id"},{data:"pending_request.branch.name",name:"branch"},{data:"pending_request.book_type.description",name:"book"},{data:"previous_pending",name:"previous_pending"},{data:"quantity",name:"quantity"},{data:"pending",name:"pending"},{data:"created_at",name:"date"},{data:"status",name:"status"},{data:"remarks",name:"remarks"},{data:"action",orderable:!1,searchable:!1}],columnDefs:[{width:30,targets:0},{width:80,targets:1},{width:250,targets:2},{width:70,targets:3},{width:70,targets:4},{width:70,targets:5},{width:130,targets:6},{width:50,targets:7},{width:160,targets:8},{width:100,targets:9}],order:[[6,"desc"]]});break;case"Release History":$("#books_release_table").DataTable({stateSave:!0,stateSaveCallback:function(e,t){localStorage.setItem("DataTables_"+e.sInstance,JSON.stringify(t))},stateLoadCallback:function(e){return JSON.parse(localStorage.getItem("DataTables_"+e.sInstance))},stateLoadParams:function(e,t){t.order&&delete t.order},initComplete:function(e,t){_()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:3},responsive:!0,ajax:{url:"/view_release_books",data:{book_type_select:r,branch_select:c}},columns:[{data:"id",name:"id"},{data:"pending_request.branch.name",name:"branch"},{data:"pending_request.book_type.description",name:"book"},{data:"previous_pending",name:"previous_pending"},{data:"quantity",name:"quantity"},{data:"pending",name:"pending"},{data:"book_range",name:"book_range"},{data:"created_at",name:"date"},{data:"status",name:"status"},{data:"remarks",name:"remarks"},{data:"action",orderable:!1,searchable:!1}],columnDefs:[{width:30,targets:0},{width:80,targets:1},{width:250,targets:2},{width:70,targets:3},{width:70,targets:4},{width:70,targets:5},{width:130,targets:6},{width:130,targets:7},{width:50,targets:8},{width:160,targets:9},{width:110,targets:10}],order:[[7,"desc"]]});break;case"Assign History":$("#books_assign_table").DataTable({stateSave:!0,stateSaveCallback:function(e,t){localStorage.setItem("DataTables_"+e.sInstance,JSON.stringify(t))},stateLoadCallback:function(e){return JSON.parse(localStorage.getItem("DataTables_"+e.sInstance))},stateLoadParams:function(e,t){t.order&&delete t.order},initComplete:function(e,t){_()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:1},responsive:!0,ajax:{url:"/view_assign_books",data:{book_type_select:r,branch_select:c,invoice_select:d}},columns:[{data:"student_name",name:"name"},{data:"student.branch.name",name:"branch"},{data:"books.book_type.description",name:"book"},{data:"books.name",name:"book_no"},{data:"books.reference_no.invoice_ref_no",name:"reference_no"},{data:"created_at",name:"date"}],columnDefs:[{width:250,targets:0},{width:80,targets:1},{width:250,targets:2},{width:100,targets:3},{width:130,targets:4},{width:200,targets:5}],order:[[5,"desc"]]});break;case"Return History":$("#books_return_table").DataTable({stateSave:!0,stateSaveCallback:function(e,t){localStorage.setItem("DataTables_"+e.sInstance,JSON.stringify(t))},stateLoadCallback:function(e){return JSON.parse(localStorage.getItem("DataTables_"+e.sInstance))},stateLoadParams:function(e,t){t.order&&delete t.order},initComplete:function(e,t){_()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:{leftColumns:1},responsive:!0,ajax:{url:"/view_books_return",data:{book_type_select:r,branch_select:c,invoice_select:d}},columns:[{data:"books.book_type.description",name:"book"},{data:"books.name",name:"book_no"},{data:"books.reference_no.invoice_ref_no",name:"invoice_ref_no"},{data:"books.branch.name",name:"branch"},{data:"stud_id",name:"student_name"},{data:"created_at",name:"date"}],columnDefs:[{width:250,targets:0},{width:130,targets:1},{width:130,targets:2},{width:70,targets:3},{width:250,targets:4},{width:130,targets:5}],order:[[5,"desc"]]});break;case"Lost History":$("#books_lost_table").DataTable({stateSave:!0,stateSaveCallback:function(e,t){localStorage.setItem("DataTables_"+e.sInstance,JSON.stringify(t))},stateLoadCallback:function(e){return JSON.parse(localStorage.getItem("DataTables_"+e.sInstance))},stateLoadParams:function(e,t){t.order&&delete t.order},initComplete:function(e,t){_()},processing:!0,scrollX:!0,destroy:!0,scrollCollapse:!0,fixedColumns:{leftColumns:1},responsive:!0,ajax:{url:"/view_books_lost",data:{book_type_select:r,branch_select:c,invoice_select:d}},columns:[{data:"books.book_type.description",name:"book"},{data:"books.name",name:"book_no"},{data:"books.reference_no.invoice_ref_no",name:"invoice_ref_no"},{data:"books.branch.name",name:"branch"},{data:"stud_id",name:"student_name"},{data:"created_at",name:"date"}],columnDefs:[{width:250,targets:0},{width:130,targets:1},{width:130,targets:2},{width:70,targets:3},{width:250,targets:4},{width:130,targets:5}],order:[[5,"desc"]]})}var e}$("body").tooltip({selector:'[data-toggle="tooltip"]',trigger:"hover"}),$('[data-toggle="tooltip"]').click(function(){$('[data-toggle="tooltip"]').tooltip("hide")}),$("#request_books_modal").on("hidden.bs.modal",function(e){$(this).find("input,textarea,select").val("").end(),$("#request_book").val("").trigger("change"),$("#request_quantity, #request_remarks").prop("disabled",!0),u=!0}),$("#release_books_modal").on("hidden.bs.modal",function(e){$(this).find("input,textarea,select").val("").end(),$("#release_book, #release_branch").val("").trigger("change"),$("#release_quantity, #release_book, #release_starting, #release_remarks").prop("disabled",!0),u=!0}),$("#assign_books_modal").on("hidden.bs.modal",function(e){$(this).find("input,textarea,select").val("").end(),$("#assign_student_name, #assign_book_type, #assign_book").val("").trigger("change"),$("#assign_book_type, #assign_book").prop("disabled",!0),u=!0}),$(".select2").select2(),$("#request_book").select2({placeholder:"Select Book"}),$("#release_branch").select2({placeholder:"Select Branch"}),$(".books_pick").on("click",function(){$(this).hasClass("disabled")||(o=$(this).text())}),$(".refresh_table").on("click",function(){m()}),$(document).on("shown.bs.tab",'a[data-toggle="tab"]',function(e){1==u&&(m(),$.fn.dataTable.tables({visible:!0,api:!0}).columns.adjust())}),m(),$("#status_select").on("change",function(){n=$(this).val(),m()}),$("#book_type_select").on("change",function(){r=$(this).val(),m()}),$("#student_status_select").on("change",function(){l=$(this).val(),m()}),$("#program_select").on("change",function(){i=$(this).val(),m()}),$("#branch_select").on("change",function(){c=$(this).val(),m()}),$("#invoice_select").on("change",function(){d=$(this).val(),m()}),$(".request_books").on("click",function(){u=!1,$("#request_books_modal").modal("toggle"),$("#request_books_modal").modal("show")}),$("#request_book").on("change",function(t){t.preventDefault(),""!=(e=$(this).val())&&$.ajax({url:"/getRequestPending/"+e,method:"get",dataType:"text",success:function(e){$("#request_previous_pending").val(e),$("#request_pending").val(e),$("#request_quantity, #request_remarks").prop("disabled",!1),$("#request_quantity, #request_remarks").val("")}})}),$("#request_quantity").keyup(function(){""==$(this).val()?$("#request_pending").val($("#request_previous_pending").val()):$("#request_pending").val(parseInt($("#request_previous_pending").val())+parseInt($("#request_quantity").val()))}),$(document).on("submit","#request_books_form",function(t){t.preventDefault();var a=$(".save_book_request"),s=document.getElementsByClassName("save_book_request")[0];s.disabled=!0,a.html("SAVING..."),e=$("#request_book").val(),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_book_request",data:$(this).serialize(),method:"POST",dataType:"text",success:function(e){$("#request_books_modal").modal("hide"),notif("Success!","Books Requested!","success","glyphicon-ok"),s.disabled=!1,a.html("SAVE CHANGES"),m()},error:function(e){swal("Error!","Something went wrong, try again.","error"),s.disabled=!1,a.html("SAVE CHANGES")}})}),$(document).on("click",".approve_request",function(){var e=$(this).attr("id");swal({title:"Approve Book Request?",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/approve_book_request/"+e,method:"get",type:"json",success:function(e){notif("Book Request Approved!","","success","glyphicon-ok"),m()}})})}),$(document).on("click",".deliver_request",function(){var e=$(this).attr("id");swal({title:"Book Request Delivered?",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/delivered_book_request/"+e,method:"get",type:"json",success:function(e){notif("Book Request Delivered!","","success","glyphicon-ok"),m()}})})}),$(document).on("click",".pending_request",function(){var e=$(this).attr("id");swal({title:"Book Request Back to Pending?",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/pending_book_request/"+e,method:"get",type:"json",success:function(e){notif("Book Request Pending Again!","","info","glyphicon-info-sign"),m()}})})}),$(document).on("click",".cancel_request",function(){var e=$(this).attr("id");swal({title:"Cancel Book Request?",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/cancel_book_request/"+e,method:"get",type:"json",success:function(e){1!=e?(notif("Book Request Cancelled!","","info","glyphicon-info-sign"),m()):swal("Cancel not allowed!","Quantity to cancel higher than current pending","info")}})})}),$("#release_branch").val("").trigger("change"),$(".release_books").on("click",function(){u=!1,$("#release_books_modal").modal("toggle"),$("#release_books_modal").modal("show")}),$("#release_branch").on("change",function(){null!=(t=$(this).val())&&($("#release_book").prop("disabled",!1),$("#release_book").val("").trigger("change"),$("#release_books_modal").find("input,textarea").val("").end(),$("#release_book").select2({placeholder:"Select Book",ajax:{url:"/get_release_books/"+t,dataType:"json",data:function(e){return{name:e.term,page:e.page}},processResults:function(e){return{results:e.results}}}}))}),$("#release_book").on("change",function(){null!=(e=$(this).val())&&$.ajax({url:"/get_release_pending/"+e+"/"+t,method:"get",dataType:"json",success:function(e){0==e.starting?(swal("Hold!","No Books Available","error"),$("#release_quantity, #release_starting, #release_remarks").prop("disabled",!0),$("#release_books_modal").find("input,textarea").val("").end()):($("#release_quantity, #release_starting, #release_remarks").prop("disabled",!1),$("#release_stocks").val(e.stocks),$("#release_previous_pending").val(e.pending),$("#release_pending").val(e.pending),$("#release_quantity").val(""),$("#release_starting, #release_start").val(e.starting))}})}),$("#release_quantity").keyup(function(){""==$(this).val()?($("#release_pending").val($("#release_previous_pending").val()),$("#release_end").val("")):(parseInt($(this).val())>parseInt($("#release_previous_pending").val())&&$(this).val($("#release_previous_pending").val()),parseInt($(this).val())>parseInt($("#release_stocks").val())&&$(this).val($("#release_stocks").val()),$("#release_pending").val(parseInt($("#release_previous_pending").val())-parseInt($(this).val())),$("#release_end").val(parseInt($("#release_start").val())+(parseInt($(this).val())-1)))}),$("#release_starting").keyup(function(){$("#release_start").val($(this).val()),""!=$("#release_quantity").val()&&$("#release_end").val(parseInt($("#release_start").val())+(parseInt($("#release_quantity").val())-1))}),$(document).on("submit","#release_books_form",function(e){e.preventDefault();var t=$(".save_book_release"),a=document.getElementsByClassName("save_book_release")[0];a.disabled=!0,t.html("SAVING..."),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_book_release",data:$(this).serialize(),method:"POST",dataType:"text",success:function(e){if(e)return swal("Releasing Stopped","Conflict! Book #"+e+" already released or lost","error"),a.disabled=!1,void t.html("SAVE CHANGES");notif("Success!","Books Released!","success","glyphicon-ok"),$("#release_books_modal").modal("hide"),a.disabled=!1,t.html("SAVE CHANGES"),m()},error:function(e){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,t.html("SAVE CHANGES")}})}),$(document).on("click",".receive_release",function(){var e=$(this).attr("id");swal({title:"Book Received?",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/received_book_release/"+e,method:"get",type:"json",success:function(e){notif("Book Release Pending Again!","","success","glyphicon-ok"),m()}})})}),$(document).on("click",".pending_release",function(){var e=$(this).attr("id");swal({title:"Book Release back to Pending?",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/pending_book_release/"+e,method:"get",type:"json",success:function(e){notif("Book Received!","","success","glyphicon-ok"),m()}})})}),$(document).on("click",".return_release",function(){var e=$(this).attr("id");swal({title:"Return Book Released?",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/return_book_release/"+e,method:"get",type:"json",success:function(e){notif("Book Returned!","","success","glyphicon-ok"),m()}})})}),$("#release_branch").select2({placeholder:"Select Branch",ajax:{url:"/get_release_branch",dataType:"json",data:function(e){return{name:e.term,page:e.page}},processResults:function(e){return{results:e.results}}}}),$(".assign_books").on("click",function(){u=!1,$("#assign_books_modal").modal("toggle"),$("#assign_books_modal").modal("show")}),$("#assign_student_name").on("change",function(){$("#assign_book_type").prop("disabled",!1),s=$(this).val(),$("#assign_book_type").select2({placeholder:"Select Book",ajax:{url:"/get_available_book_type/"+s,dataType:"json",data:function(e){return{name:e.term,page:e.page}},processResults:function(e){return{results:e.results}}}})}),$("#assign_book_type").on("change",function(){$("#assign_book").prop("disabled",!1),e=$(this).val(),$("#assign_book").select2({placeholder:"Select Book No.",ajax:{url:"/get_available_book/"+e,dataType:"json",data:function(e){return{name:e.term,page:e.page}},processResults:function(e){return{results:e.results}}}})}),$(document).on("submit","#assign_books_modal",function(e){e.preventDefault();var t=$(".save_book_assign"),a=document.getElementsByClassName("save_book_assign")[0];a.disabled=!0,t.html("SAVING..."),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_book_assign",data:$("#assign_books_form").serialize(),method:"POST",dataType:"text",success:function(e){$("#assign_books_modal").modal("hide"),notif("Success!","Book Assigned!","success","glyphicon-ok"),a.disabled=!1,t.html("SAVE CHANGES"),m()},error:function(e){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,t.html("SAVE CHANGES")}})}),$("#assign_student_name").select2({placeholder:"Select Student",ajax:{url:"/get_assign_student",dataType:"json",data:function(e){return{name:e.term,page:e.page}},processResults:function(e){return{results:e.results}}}}),$(document).on("click",".lost_book",function(){var e=$(this).attr("id");swal({title:"Lost Book?",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/lost_book/"+e,method:"get",type:"json",success:function(e){notif("Book Lost!","","info","glyphicon-info-sign"),m()}})})}),$(document).on("click",".return_book",function(){var e=$(this).attr("id");swal({title:"Return Book?",text:"This book will be available again",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/return_book/"+e,method:"get",type:"json",success:function(e){notif("Success!","Book Returned!","success","glyphicon-ok"),m()}})})})})}});