<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $name }}</title>
</head>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<body>

<table border = "0" cellpadding="10">
<!-- 第一列 -->
<tr>
    <td>
    選擇付款方式
    </td>
    <td>
    選擇支付名稱
    </td>
    <td>
    選擇代付名稱
    </td>
    <td>
    選擇商戶
    </td>
    <td>
    生成 log 連結
    </td>
</tr>
<!-- 第二列 -->
<tr>
    <td>
    <!-- 選擇付款方式 -->
    <select id="selectPayment">
      <optgroup label="支付">
        <option value="payRecord">下單</option>
        <option value="payNotifyThird">回調</option>
        <option value="payNotify">後台更新</option>
      </optgroup>

      <optgroup label="代付">
        <option value="repayRecord">下單</option>
        <option value="repayNotifyThird">回調</option>
        <option value="repayQueryRecord">查單</option>
        <option value="repayNotify">後台更新</option>
      </optgroup>
    </select>
    </td>
    <td>
    <!-- 選擇支付名稱 -->
    <select id="selectPayId">
        <option value="zunxiang.log">zunxiang</option>
        <option value="14926056">002</option>
        <option value="16652314">003</option>
        <option value="13525653">004</option>
        <option value="17937997">005</option>
        <option value="17012008">006</option>
        <option value="18594787">007</option>
        <option value="19271811">008</option>
        <option value="15545334">009</option>
        <option value="15643160">010</option>
        <option value="16698274">011</option>
        <option value="12291471">012</option>
        <option value="14663141">013</option>
        <option value="11701541">014</option>
        <option value="11875039">015</option>
        <option value="16909384">016</option>
        <option value="10862161">017</option>
        <option value="19179955">018</option>
    </select>
    </td>
    <td>
    <!-- 選擇代付名稱 -->
    <select id="selectRepayId">
        <option value="12296514">001</option>
        <option value="14926056">002</option>
        <option value="16652314">003</option>
        <option value="13525653">004</option>
        <option value="17937997">005</option>
        <option value="17012008">006</option>
        <option value="18594787">007</option>
        <option value="19271811">008</option>
        <option value="15545334">009</option>
        <option value="15643160">010</option>
        <option value="16698274">011</option>
        <option value="12291471">012</option>
        <option value="14663141">013</option>
        <option value="11701541">014</option>
        <option value="11875039">015</option>
        <option value="16909384">016</option>
        <option value="10862161">017</option>
        <option value="19179955">018</option>
    </select>
    </td>
    <td>
    <!-- 選擇商戶 -->
    <select id="selectId">
        <option value="19271811">008</option>
        <option value="12296514">001</option>
        <option value="14926056">002</option>
        <option value="16652314">003</option>
        <option value="13525653">004</option>
        <option value="17937997">005</option>
        <option value="17012008">006</option>
        <option value="18594787">007</option>
        <option value="15545334">009</option>
        <option value="15643160">010</option>
        <option value="16698274">011</option>
        <option value="12291471">012</option>
        <option value="14663141">013</option>
        <option value="11701541">014</option>
        <option value="11875039">015</option>
        <option value="16909384">016</option>
        <option value="10862161">017</option>
        <option value="19179955">018</option>
    </select>
    </td>
    <td>
    <!-- 生成 log 連結 -->
    <button id="getLog" onclick="getLog(this);">生成 log 連結</button>
    <!-- <a href="javascript:void(0);" onclick="getId(this);" value="Mike">click me</a> -->
    </td>
</tr>
</table>

<div id="urlLog">
<b>Log 線上查詢</b>
<a id="log" href=""></a>
</div>

</body>
</html>

<script>
  // 隐藏选择的元素
  document.getElementById('urlLog').style.display = 'none';

  function getLog(ele) {
    var url = 'http://47.57.13.151:2222/paycenter/log/';
    var apiUrl = 'http://127.0.0.1:9501/Get/log';

    var date = new Date();
    var now = date.getFullYear() + '' + (date.getMonth() + 1 >= 10 ? date.getMonth() + 1 : '0' + (date.getMonth() + 1));

    var selectPayment = document.getElementById("selectPayment");
    var selectId = document.getElementById("selectId");
    var selectPayId = document.getElementById("selectPayId");

    url = url + selectPayment.value + '/' + now + '/' + selectId.value + '_' + selectPayId.value;

    var log = document.getElementById("log");
    log.href = apiUrl + '?url=' + url;
    log.text = 'selectId -> ' + selectId.value + ' & selectPayId -> ' + selectPayId.value;

    var urlLog = document.getElementById("urlLog");
    // 以块级样式显示
    urlLog.style.display = 'block';
  }
</script>
