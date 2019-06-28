@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Secret ID</th>
                    <th scope="col">Secret</th>
                    <th scope="col">Admin</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Active</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{$row->name}}</td>
                    <td>{{$row->email}}</td>
                    <td>{{$row->secretid}}</td>
                    <td>{{$row->secret}}</td>
                    <td>
                    @if($row->id==Auth::user()->id)
                        <button class="btn btn-success"disabled>You</button>
                    @elseif($row->admin==1)
                        <a href="toAdmin/{{$row->id}}" class="btn btn-primary">Yes</a>
                    @else
                        <a href="toAdmin/{{$row->id}}" class="btn btn-danger">No</a>
                    @endif
                    </td>
                    <td>{{$row->created_at}}</td>
                    <td>
                    @if($row->id==Auth::user()->id)
                        <button class="btn btn-success"disabled>You</button>
                    @elseif($row->active==1)
                        <a href="toActive/{{$row->id}}" class="btn btn-primary">Yes</a>
                    @else
                        <a href="toActive/{{$row->id}}" class="btn btn-danger">No</a>
                    @endif
                    </td>
                    <td><a href="generateToken/{{$row->id}}" class="btn btn-warning">Generate Token</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($data->lastPage()>1)
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item {{ ($data->currentPage() == 1) ? ' disabled' : '' }}"><a class="page-link" href="{{ $data->url(1) }}">Previous</a></li>
                @for($i=1;$i<=$data->lastPage();$i++)
                <li class="page-item"><a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a></li>
                @endfor
                <li class="page-item {{ ($data->currentPage() == $data->lastPage()) ? ' disabled' : '' }}"><a class="page-link" href="{{ $data->url($data->currentPage()+1) }}">Next</a></li>
            </ul>
        </nav>
        @endif
        </div>
        <script>
        $(document).ready(function(){
            $('#gambar').on('change',function(){
                //get the file name
                var fileName = $(this).val();
                //replace the "Choose a file" label
                console.log(fileName);
                $(this).next('.custom-file-label').html(fileName);
            });
        });
        </script>
    </div>
</div>
@endsection
