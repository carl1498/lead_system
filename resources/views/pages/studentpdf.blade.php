<table cellspacing="0">
    <tbody style="border-style: solid;">
        <tr>
            <td width="430px"><h1 style="float: right;">{{ $student->lname }}, {{ $student->fname }} {{ $student->mname }}</h3></td>
            
            <td width="100px" rowspan="4"><img id="p_picture" class="profile-user-img img-responsive img-rounded" src="./storage/img/student/{{$student->picture}}" style="width: 100px; height: 100px;"></td>
        </tr>
        <tr>
            <td><h3>{{ $student->contact }}</h3></td>
        </tr>
        <tr>
            <td><h3>{{ $student->email }}</h3></td>
        </tr>
        <tr>
            <td>{{ $student->address }}</td>
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
            <td><b>Date of Birth:</b> {{ $student->birthdate }}</td>
        </tr>
        <tr>
            <td><b>Age:</b> {{ $student->age }}</td>
        </tr>
        <tr>
            <td><b>Gender:</b> {{ $student->gender }}</td>
        </tr>
        <tr>
            <td><b>Course:</b> {{ $student->course->name }}</td>
        </tr>

        <tr>
            <td></td>
        </tr>
        <hr>
        <tr>
            <td></td>
        </tr>

        <tr>
            <td><h2>Student Information</h2></td>
        </tr>

        <tr>
            <td></td>
        </tr>

        <tr>
            <td width="350px"><b>Program:</b> @if($student->program){{ $student->program->name }}@endif</td>
            <td width="300px"><b>Status: </b> {{ $student->status }}</td>
        </tr>
        <tr>
            <td><b>School:</b> @if($student->school){{ $student->school->name }}@endif</td>
            <td width="300px"><b>COE Status: </b> {{ $student->coe_status }}</td>
        </tr>
        <tr>
            <td><b>Benefactor:</b> @if($student->benefactor){{ $student->benefactor->name }}@endif</td>
            <td><b>Branch: </b> {{ $student->branch->name }}</td>
        </tr>
        <tr>
            <td><b>Company (Trainees Only):</b> @if($student->company){{ $student->company->name }}@endif</td>
            <td><b>Referred By: </b> {{ $student->referral->fname }}</td>
        </tr>
        <tr>
            <td><b>Current Class:</b> {{ $student->class }}</td>
            <td><b>Sign Up Date: </b> {{ $student->date_of_signup }}</td>
        </tr>
        <tr>
            <td><b>Remarks:</b> {{ $student->remarks }}</td>
            @if($student->departure_year && $student->departure_month)
            <td><b>Departure: </b> {{ $student->departure_year->name }} {{ $student->departure_month->name }}</td>
            @else
            <td><b>Departure: </b> N/A</td>
            @endif
        </tr>
        
        <tr>
            <td></td>
        </tr>

        <tr>
            <td><h3>Books Owned</h3></td>
        </tr>

        <tr>
            <td></td>
        </tr>

        <tr>
            <td><b>Minna I:</b> @if($student->minna1) #{{ $student->minna1 }}@endif</td>
        </tr>
        <tr>
            <td><b>Minna I WB:</b> @if($student->minna1wb) #{{ $student->minna1wb }}@endif</td>
        </tr>
        <tr>
            <td><b>Minna II:</b> @if($student->minna2) #{{ $student->minna2 }}@endif</td>
        </tr>
        <tr>
            <td><b>Minna II WB:</b> @if($student->minna2wb) #{{ $student->minna2wb }}@endif</td>
        </tr>
        <tr>
            <td><b>Kanji:</b> @if($student->kanji) #{{ $student->kanji }}@endif</td>
        </tr>

    </tbody>

</table>
