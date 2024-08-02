<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alzoko-Pago</title>
    <script
      src="https://www.paypal.com/sdk/js?client-id=AcohzUfOMY_KjkBO4cjSvkFNaRKmjR8EX4ofFISRnJG6vfazEEI_JaurRw6YK0fZs4mBrf65ad9YG0RJ"
      ></script>
</head>

<body>
    <div id="paypal-button-container"></div> 
    <script>
       paypal.Buttons({
        style:{
          color:'blue',
          shape:'pill',
          label:'pay',

        },
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: 100
              }
            }]
          })
        },
        onApprove: function(data, actions){
          actions.order.capture(). then (function (detalles){
            window.location.href="completado.html"

          });

        },
        
        onCancel: function(data){
          alert("Pago Cancelado");
          console.log(data)


        }
       }).render('#paypal-button-container');
      </script> 


</body>
</html>