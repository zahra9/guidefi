<!DOCTYPE html>
<style>
    .text-center{
        text-align: center;
    }
    .head-color {
    color: #296a81;
}
.bg-color{
    background-color: #DDF2F8;
}
table{
    border-collapse: collapse;
    margin-left: auto;
    margin-right: auto;
}
.table-bg{
    background-color: #fff;
}
tr td{
    text-align: center;
}
tr th{
    border-bottom: 1px solid #D8DDE2;
    border-right: 1px solid #D8DDE2;
    padding: 10px;
}
tr td{
    border-bottom: 1px solid #D8DDE2;
    padding: 10px 20px;
    border-right: 1px solid #D8DDE2;
}
</style>
<head>
    <title>Guidefi Statistics</title>
</head>

<body class="bg-color">
<img src="{{ public_path('dummy.png') }}" width="20%" style="padding-top: 25px">
<h2 class="text-center">Guidefi Statistics</h2>
<table class="table-bg" >
    <tr>
        <th>Total Users</th>
        <td>{{$data['total_users']}}</td>
    </tr>
    <tr>
        <th>Paid Users</th>
        <td>{{$data['paid_users']}}</td>
    </tr>
    <tr>
        <th>Unpaid Users</th>
        <td>{{$data['unpaid_user']}}</td>
    </tr>
    <tr>
        <th>Total Clients</th>
        <td>{{$data['total_clients']}}</td>
    </tr>
    <tr>
        <th>Total Advisors</th>
        <td>{{$data['total_advisors']}}</td>
    </tr>
    <tr>
        <th>Today Profit</th>
        <td>{{$data['today_profit']}}</td>
    </tr>
    <tr>
        <th>This Week Profit</th>
        <td>{{$data['this_week_profit']}}</td>
    </tr>
    <tr>
        <th>This Month Profit</th>
        <td>{{$data['this_year_profit']}}</td>
    </tr>
    <tr>
        <th>This Year Profit</th>
        <td>{{$data['this_year_revenue']}}</td>
    </tr>
    <tr>
        <th>This Month Revenue</th>
        <td>{{$data['this_month_revenue']}}</td>
    </tr>
    <tr>
        <th>Competed Discovery Call</th>
        <td>{{$data['competed_discovery_call']}}</td>
    </tr>
    <tr>
        <th>Discovery Calls In Progress</th>
        <td>{{$data['waiting_discovery_call']}}</td>
    </tr>
</table>

</body>
