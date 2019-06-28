@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form class="input-group" enctype="multipart/form-data"  method="POST" action="upload">
            @csrf
                <div class="custom-file">
                    <input type="file" accept="image/jpeg,image/png" name="image" class="custom-file-input" id="gambar" aria-describedby="gambarAddon">
                    <label class="custom-file-label" for="gambar">Choose file</label>
                </div><br>
                <input class="btn btn-primary" type="submit" value="Upload">
            </form>
        </div>
        <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Preview</th>
                    <th scope="col">Old Name</th>
                    <th scope="col">New Name</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Hapus</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    @if(Storage::has('images/user_id_'.Auth::user()->id.'/'.$row->filename))
                    <td><img src="data:image/jpeg;base64,{{base64_encode(Storage::get('images/user_id_'.Auth::user()->id.'/'.$row->filename))}}" style="width:100px"></td>
                    @else
                    <td>Gambar Tidak Ada</td>
                    @endif
                    <td>{{$row->title}}</td>
                    <td>{{$row->filename}}</td>
                    <td>{{$row->created_at}}</td>
                    <td><a href="delete/{{$row->id}}" class="btn btn-danger">Hapus</a></td>
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
                fileName=fileName.split("\\").pop();
                $(this).next('.custom-file-label').html(fileName);
            });
        });
        </script>
    </div>
</div>
@endsection
