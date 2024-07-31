function callFileUploadBtn(){
    $("#picture-deposit").trigger('click');
}

function hideShowAmount(value){
    if (value == 'H'){
        $("#my-wallet-value").hide();
        $("#my-wallet-value-hidden").show();
        $("#hide-amount").removeAttr("onclick");
        $("#hide-amount").removeAttr("onMouseOver");
        $("#hide-amount").attr("onclick", "hideShowAmount('O')");
        $("#hide-amount").removeAttr("class");
        $("#hide-amount").attr("class", "fa fa-eye-slash fa-lg");
        $("#hide-amount").attr("onMouseOver", "this.style.cursor='pointer'");
        $("#transactions-list").html("");
    } else {
        $("#my-wallet-value-hidden").hide();
        $("#my-wallet-value").show();
        $("#hide-amount").removeAttr("onclick");
        $("#hide-amount").removeAttr("onMouseOver");
        $("#hide-amount").attr("onclick", "hideShowAmount('H')");
        $("#hide-amount").removeAttr("class");
        $("#hide-amount").attr("class", "fa fa-eye fa-lg");
        $("#hide-amount").attr("onMouseOver", "this.style.cursor='pointer'");
    }
}

// Previem Check Image
function readUpload() {
    var file = $('#picture-deposit').val();
    var fileName = file.split('\\').pop();
    $('#upload-name').show(500);
    $('#upload-name').html("<img id='output'/>");

    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src)
    }
}


function closeTransactionModal(){
    $("#amount-deposit").val("");
    $("#description-deposit").val("");
    $("#picture-deposit").val("");
    $('#upload-name').hide(500);
    $("#success-check-deposit").hide();
    $("#error-check-deposit").hide();

    $("#amount-purchase").val("");
    $("#date-purchase").val("");
    $("#description-purchase").val("");
    $("#success-purchase").hide();
    $("#error-purchase").hide();
}


function depositCheck(){

    var validation = true;

    if ($("#amount-deposit").val() == ""){
        validation = false;
    }

    if ($("#description-deposit").val() == ""){
        validation = false;
    }

    if ($("#picture-deposit").val() == ""){
        validation = false;
    }

    if (validation){

        var fileNamePost = $('#picture-deposit').val();
        var fileNamePostExt = fileNamePost.split('\\').pop();

        $.ajax({
        url : 'api/create-transaction',
        type : 'POST',
        data: {
            "auth_request":$('#auth-request').val(),
            "amount":$('#amount-deposit').val(),
            "description": $("#description-deposit").val(),
            "check_picture": fileNamePost,
            "transaction_type": 'C',
        },
        dataType: 'json',
        success: function(data){
            console.log(data);
            $("#success-check-deposit").html("Nice! Your transaction is now in the review process. Please wait!");
            $("#error-check-deposit").hide();
            $("#success-check-deposit").show(500);
            $("#amount-deposit").val("");
            $("#description-deposit").val("");
            $('#upload-name').hide(500);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        }
        });
    } else {
        $("#error-check-deposit").html("Ops, something is missing...");
        $("#success-check-deposit").hide();
        $("#error-check-deposit").show(500);
    }
}



function purchaseItem(){

    var validation = true;

    if ($("#amount-purchase").val() == ""){
        validation = false;
    }

    if ($("#date-purchase").val() == ""){
        validation = false;
    }


    if ($("#description-purchase").val() == ""){
        validation = false;
    }

    if (validation){
        $.ajax({
        url : 'api/create-transaction',
        type : 'POST',
        data: {
            "auth_request":$('#auth-request').val(),
            "amount":$('#amount-purchase').val(),
            "description": $("#description-purchase").val(),
            "transaction_type": 'P',
        },
        dataType: 'json',
        success: function(data){
            console.log(data);

            if (data.error == "enough-balance"){
                $("#error-purchase").html("Ops, you do not have enough balance!");
                $("#success-purchase").hide();
                $("#error-purchase").show(500);
            } else {
                $("#success-purchase").html("Nice! Purchase registered!");
                $("#error-purchase").hide();
                $("#success-purchase").show(500);
                $("#amount-purchase").val("");
                $("#description-purchase").val("");
                getMyWallet();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        }
        });
    } else {
        $("#error-purchase").html("Ops, something is missing...");
        $("#success-purchase").hide();
        $("#error-purchase").show(500);
    }

}


function getMyWallet(){
    $("#my-wallet-value").html("Loading...");
    var id = $("#auth-request").val();
    $.ajax({
    url : 'api/user-wallet/'+id,
    type : 'GET',
    dataType: 'json',
    success: function(data){
        console.log(data);

        if (data.wallet == "0.00"){
            $("#my-wallet-value").html("$0.00");
            $("#see-more").hide();
        } else {
            $("#my-wallet-value").html("$"+data.wallet[0].amount);
            $("#see-more").show(500);
        }
    },
    error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        console.log(thrownError);
    }
    });
}

// Manage Check deposits
function approveDenyDeposit(id, value){
    var aval_value;
    if (value == 1){
        aval_value = 'A';
    } else {
        aval_value = 'D';
    }
    $.ajax({
    url : 'api/manage-check-deposit',
    type : 'PUT',
    data: {
            "transaction_id":id,
            "transaction_aval_value":aval_value,
        },
    dataType: 'json',
    success: function(data){
        console.log(data);
        $("#table-results").html("");
        $("#table-trs").html("");
        getAllPendingChecks();
    },
    error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        console.log(thrownError);
    }
    });
}


function getAllPendingChecks(){

    $.ajax({
    url : 'api/get-all-pending-checks',
    type : 'GET',
    dataType: 'json',
    success: function(data){
        console.log(data);

        if (data.transactionsList == null){
            $("#table-results").html("<div class='alert alert-warning'>Ops, there's nothing to show.</div>")
            $("#table-title").html("");
        } else {

            $("#table-trs").append("<th>#</th><th>Description</th><th>Amount (USD)</th><th>Actions</th>");

            for (var i=0; i < data.transactionsList.length; i++) {

                var dateFormated = data.transactionsList[i].created_at.split('T');
                var dateFormat = dateFormated[0];

                hourFormated = dateFormated[1].split('.');
                hourFormat = hourFormated[0];

                $("#table-results").append("<tr>"+
                                                    "<td>"+data.transactionsList[i].id+""+
                                                    "<td><strong>"+data.transactionsList[i].description+"</strong><br><small>"+dateFormat+" at "+hourFormat+"</small></td>"+
                                                    "<td>$"+data.transactionsList[i].amount+"</td>"+
                                                    "<td><button type='button' class='items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-success text-white' title='Click to Approve this Pendind Check' onclick='approveDenyDeposit("+data.transactionsList[i].id+", 1)'><i class='fa fa-thumbs-up'></i></button> <button type='button' class='items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-danger text-white' title='Click to Deny this Pendind Check' onclick='approveDenyDeposit("+data.transactionsList[i].id+", 2)'><i class='fa fa-thumbs-down'></i></button></td>"+
                                                    "</tr>");
            }

            $("#table-title").html("");
        }
    },
    error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        console.log(thrownError);
    }
    });

}


function getMyTransactions(){
    $("#transactions-list").html("Loading...");
    var id = $("#auth-request").val();
    $.ajax({
    url : 'api/get-my-transactions/'+id,
    type : 'GET',
    dataType: 'json',
    success: function(data){
        console.log(data);
        $("#transactions-list").html("");

        if (data.transactions.warning == "0.00"){
            $("#transactions-list").html(data.transactions[0].warning);
        } else {
            for (var i=0; i < data.transactions.length; i++) {
                if(data.transactions[i].transaction_type == 'C'){
                    if(data.transactions[i].transaction_status == 'A'){
                        transactionType = "<span class='text-success'>Check Deposit Approved</span>";
                    }
                    if(data.transactions[i].transaction_status == 'P'){
                        transactionType = "<span class='text-warning'>Check Deposit Pending</span>";
                    }
                    if(data.transactions[i].transaction_status == 'D'){
                        transactionType = "<span class='text-danger'>Check Deposit Denied</span>";
                    }
                    signal = "";
                } else {
                    transactionType = "<span class='text-danger'>Expense</span>";
                    signal = "-";

                }

                var dateFormated = data.transactions[i].created_at.split('T');
                var dateFormat = dateFormated[0];

                hourFormated = dateFormated[1].split('.');
                hourFormat = hourFormated[0];

                $("#transactions-list").append("<div class='alert alert-light'><small>"+dateFormat+" at "+hourFormat+"</small> | <strong>"+data.transactions[i].description+"</strong><hr/><strong>"+signal+"$"+data.transactions[i].amount+"</strong><br/><small>"+transactionType+"</small></div>");
            }
        }
    },
    error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        console.log(thrownError);
    }
    });
}
