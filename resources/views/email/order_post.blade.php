<p>您的单号为：{{$order->order_num}} 的订单已发货</p>

<h4>其中包含的商品为：</h4>

<ul>
    @foreach($order->orderDetails as $details)
        <li>{{$details->goods->title}}</li>
        <li>单价：{{$details->price}}</li>
        <li>数量：{{$details->num}}</li>
    @endforeach
</ul>
<h5>总金额：{{$order->amount}}</h5>
