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
    <h1>{{$listWebinarParticipant[0]->title}}</h1>
    <table>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Pekerjaan</th>
        <th>Email</th>
        <th>No Telepon</th>
        <th>Asal Provinsi</th>
        <th>Asal Kota</th>
      </tr>
      @foreach ($listWebinarParticipant as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$item->name}}</td>
            <td>{{!empty($item->job) ? $item->job : " - "}}</td>
            <td>{{$item->email}}</td>
            <td>{{$item->phone}}</td>
            <td>{{!empty($item->province_name) ? $item->province_name : " - "}}</td>
            <td>{{!empty($item->city_name) ? $item->city_name : " - "}}</td>
        </tr>              
      @endforeach
    </table>
  </body>
</html>