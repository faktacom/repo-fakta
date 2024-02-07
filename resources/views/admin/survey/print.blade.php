<!DOCTYPE html>
<html>
  <head>
    <style>
      table {
        width: 80%;
        border-collapse: collapse;
        margin: auto;
      }

      th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
      }

      th {
        background-color: lightgray;
      }

      h1 {
        text-align: center;
      }
    </style>
  </head>
  <body>
    <h1>{{$list_survey_respond[0]->survey_name}}</h1>
    <table>
      <tr>
        <th>No</th>
        <th>Created Date</th>
        <th>Nama Responden</th>
        <th>Email</th>
        <th>No Telepon</th>
        <th>Jenis Kelamin</th>
        <th>Pekerjaan</th>
        <th>Tanggal Lahir</th>
        <th>Asal Provinsi</th>
        <th>Nama Kota</th>
      </tr>
      @foreach ($list_survey_respond as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$item->created_date}}</td>
            <td>{{$item->name}}</td>
            <td>{{$item->email}}</td>
            <td>{{$item->phone}}</td>
            <td>{{$item->gender}}</td>
            <td>{{$item->job}}</td>
            <td>{{$item->birth_date}}</td>
            <td>{{$item->province_name}}</td>
            <td>{{$item->city_name}}</td>
        </tr>              
      @endforeach
    </table>
  </body>
</html>