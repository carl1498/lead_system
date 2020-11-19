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

        <tr>
            <td><h2>Employee Info</h2></td>
        </tr>

        <tr>
            <td></td>
        </tr>

        <tr>
            <td><b>Company:</b> {{ $emp->company_type->name }}</td>
        </tr>
        <tr>
            <td><b>Position:</b> {{ $emp->role->name }}</td>
        </tr>
        <tr>
            <td><b>Hired Date:</b> {{ $emp->emp_history }}</td>
        </tr>
        <tr>
            <td><b>Employment Status:</b> {{ $emp->employment_status }}</td>
        </tr>
        <tr>
            <td><b>Employment Type:</b> {{ $emp->employment_type }}</td>
        </tr>

        <tr>
            <td></td>
        </tr>
        <hr>
        <tr>
            <td></td>
        </tr>

        <tr>
            <td><h2>In Case of Emergency</h2></td>
        </tr>

        <tr>
            <td></td>
        </tr>

        @if(count($emp->employee_emergency) >= 1)
        <tr>
            <th width="180px" style="font-weight: bold;">Name</th>
            <th width="100px" style="font-weight: bold;">Relationship</th>
            <th width="100px" style="font-weight: bold;">Contact</th>
            <th width="100px" style="font-weight: bold;">Address</th>
        </tr>
            @foreach($emp->employee_emergency as $e)
            <tr>
                <th width="180px">{{ $e->lname }}, {{ $e->fname }} {{ $e->mname }}</th>
                <th width="100px">{{ $e->relationship }}</th>
                <th width="100px">{{ $e->contact }}</th>
                <th width="180px">{{ $e->address }}</th>
            </tr>
            @endforeach
        @else
            <tr>
                <td><h3>N/A</h3></td>
            </tr>
        @endif

        <tr>
            <td></td>
        </tr>
        <hr>
        <tr>
            <td></td>
        </tr>

        <tr>
            <td><h2>Employment History</h2></td>
        </tr>

        @if(count($emp->employment_history) >= 1)
        <tr>
            <th width="200px" style="font-weight: bold;">Company</th>
            <th width="150px" style="font-weight: bold;">Position</th>
            <th width="180px" style="font-weight: bold;">Employment Duration</th>
        </tr>
            @foreach($emp->employment_history as $e)
            <tr>
                <th width="200px">{{ $e->name }}</th>
                <th width="150px">{{ $e->designation }}</th>
                <th width="180px">{{ $e->start }} - {{ $e->finished }}</th>
            </tr>
            @endforeach
        @else
            <tr>
                <td><h3>N/A</h3></td>
            </tr>
        @endif
        

    </tbody>
</table>