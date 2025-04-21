<style>
    table.marksheet {
        width: 100%;
        border-collapse: collapse;
    }
    table.marksheet th, table.marksheet td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
    }
    table.marksheet th {
        background-color: #f9f9f9;
    }

    @media print {
        form, button {
            display: none;
        }
    }
</style>

<table class="marksheet">
    <thead>
        <tr>
            <th>Student</th>
            @foreach($subjects as $subject)
                <th>{{ $subject->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                @foreach($subjects as $subject)
                    @php
                        $result = $student->results->firstWhere('subject_id', $subject->id);
                    @endphp
                    <td>{{ $result ? $result->score : '-' }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<form action="{{ route('dos.results.compile') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary mt-3">Compile & Forward to Head Teacher</button>
</form>

<button onclick="window.print()" class="btn btn-secondary mt-2">Print Marksheet</button>
