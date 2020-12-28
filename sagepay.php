<section class="page_descp">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
                  

<div class="wrapper">

<div>
    <form class="form">
        <div class="form-group">
            <label class="control-label">Select your gift amount</label>
            <input type="radio" name="gift-amount" value="50" class="exampleInputDollar form-control currency">$50<br>
            <input type="radio" name="gift-amount" value="100"  class="exampleInputDollar form-control currency">$100<br>
            <input type="radio" name="gift-amount" value="250"  class="exampleInputDollar form-control currency">$250<br>
            <input type="radio" name="gift-amount" value="500"  class="exampleInputDollar form-control currency">$500<br>
            <input type="radio" name="gift-amount" value=""  class="exampleInputDollar form-control currency">Other <input type="number"  class="form-control currency" name="other_reason" id="exampleInputDollar" placeholder="Amount"/>​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​                
            
        </div>
        <button class="btn btn-primary" id="paymentButton">Pay Now</button>
    </form>
    <div id="paymentDiv" hidden></div>
    <br /><br />
    <h5>Results:</h5>
    <p style="width:100%"><pre><code id="paymentResponse"></code></pre></p>
</div>
</div>

</div>
</div>
</div>
</section>
<script type="text/javascript">

// this time, when the user submits, we'll send the amount to a server-side
// script that returns the data we'll need for initialization.

PayJS(['PayJS/UI','PayJS/CORE', 'jquery'],
function($UI, $CORE, $) {
    $("#paymentButton").click(function() {
        $(this).prop('disabled', true);
        $("#paymentResponse").text("The response will appear here as JSON, and in your browser console as a JavaScript object.");
        
        var amt = $('input[name=gift-amount]:checked').val();


        if (amt == ""){
            var amt = $("#exampleInputDollar").val();
        }
        amt = parseFloat(amt).toFixed(2);

        amt = parseFloat(amt);

         

        $.get(
            '/wp-content/themes/Northen/auth.php',
            {
                amount: amt,
            },

            function(authResp) {

                $CORE.setIsRecurring(true);

                $CORE.setRecurringSchedule({
                "amount": amt,
                "interval": 3,
                "frequency": "Monthly",
                "totalCount": 4,
                "nonBusinessDaysHandling": "After",
                "startDate": new Date($.now()),
                "groupId": "123456"  });



                $UI.Initialize({
                    clientId: authResp.clientId,
                    merchantId: authResp.merch,
                    authKey: authResp.authKey,
                    requestType: "payment",
                    orderNumber: authResp.invoice,
                    amount: amt,
                    elementId: "paymentDiv",
                    postbackUrl: authResp.postback,
                    salt: authResp.salt,
                    addFakeData: true
                });
                $UI.setCallback(function($RESP) {
                    console.log("Ajax Response:");
                    console.log($RESP.getAjaxResponse());
                    console.log("API Response:");
                    console.log($RESP.getApiResponse());
                    console.log("Gateway Response:");
                    console.log($RESP.getGatewayResponse());
                    console.log("API Response + Hash:");
                    console.log($RESP.getResponseHash())
                    $("#paymentResponse").text(
                        $RESP.getApiResponse()
                    );
                    $("#paymentButton").prop('disabled', false);
                });
                $("#paymentDiv").show('slow');
            },
            "json"
        );
    });
});
</script>
