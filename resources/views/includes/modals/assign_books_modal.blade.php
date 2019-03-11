<div class="modal fade" id="assign_books_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Assign Books to Student</h4>
            </div>
            <form class="form-horizontal" id="assign_books_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="add_edit" id="add_edit">

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="assign_student_name" class="pull-right">Student Name</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <select type="text" id="assign_student_name" name="assign_student_name" class="form-control select2 required" style="width: 100%;" required>
                                        <!-- Controller: assignBooksController@get_student -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="assign_book_type" class="pull-right">Book</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <select type="text" id="assign_book_type" name="assign_book_type" class="form-control required" style="width: 100%;" required disabled>
                                        <!-- Controller: releaseBooksController@get_books -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="assign_book" class="pull-right">Book No.</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <select type="text" id="assign_book" name="assign_book" class="form-control select2 required" style="width: 100%;" required disabled>
                                        <!-- Controller: releaseBooksController@get_books -->
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_book_assign">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>