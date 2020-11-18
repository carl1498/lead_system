<table cellspacing="0">
    <tbody style="border-style: solid;">

        <tr>
            <td width="430px"><h1 style="float: right;">{{ $emp->lname }}, {{ $emp->fname }} {{ $emp->mname }}</h3></td>

            <td width="100px" rowspan="5"><img id="p_picture" class="profile-user-img img-responsive img-rounded" src="./storage/img/employee/{{$emp->picture}}" style="width: 100px; height: 100px;"></td>
        </tr>
        <tr>
            <td><h3>Business #: {{ $emp->contact_business }}</h3></td>
        </tr>
        <tr>
            <td><h3>Personal #: {{ $emp->contact_personal }}</h3></td>
        </tr>
        <tr>
            <td><h3>{{ $emp->email }}</h3></td>
        </tr>
        <tr>
            <td>{{ $emp->address }}</td>
        </tr>

        <hr>
        <tr>
            <td></td>
        </tr>

        <tr>
            <td><h2>Personal Information</h2></td>
        </tr>

        <tr>
            <td></td>
        </tr>

        <tr>
            <td><b>Date of Birth:</b> {{ $emp->birthdate }}</td>
        </tr>
        <tr>
            <td><b>Age:</b> {{ $emp->age }}</td>
        </tr>
        <tr>
            <td><b>Gender:</b> {{ $emp->gender }}</td>
        </tr>

        <tr>
            <td></td>
        </tr>
        <hr>
        <tr>
            <td></td>
        </tr>

        

    </tbody>
</table>