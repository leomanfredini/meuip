<?php

function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


$user_ip = getUserIP();


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ferramenta para verificar seu endereço IPv4 de conexão com a internet">
    <meta name="author" content="Leonardo Manfredini">
    <meta name="generator" content="">

    <title>Meu IP</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="sheet.css" rel="stylesheet">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

</head>
<body>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>

<main>
  <div class="b-example-divider"></div>
      <div class="bg-dark text-secondary px-4 py-5 text-center">
        <div class="py-1">
          <h1 class="display-5 fw-bold text-white">Seu endereço IPv4 é</h1>
          <h1 class="display-5 fw-bold text-white"><?php echo $user_ip; ?></h1>
        </div>
      </div>
  </div>


  <div class="b-example-divider mb-0"></div>
    <div id="app" class="px-4 pt-5 my-1 text-center border-bottom">
          <!--<h1 class="display-4 fw-bold"></h1>//-->

        <div class="col-lg-6 mx-auto">
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-4">
                <!--<button type="button" class="btn btn-primary btn-lg" @click="loadNextImage">Ver Outro</button>-->
            </div>
        </div>

          <div class="overflow-hidden" style="max-height: 30vh;">
              <div class="container px-1">
                  <!--<img :src="image.url" class="img-fluid border rounded-3 shadow-lg mb-4" alt="Cat" width="700" height="500" loading="lazy">-->
                  
              </div>
          </div>



    </div>
   </div>




</main>

<script>
    new Vue({ 
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            image: { url: ""}
        },
        created(){
            this.loadNextImage();
        } ,
        methods:{
            async loadNextImage()
            {
                try{
                    axios.defaults.headers.common['x-api-key'] = "live_6HP53YmlSEFvMnUZCJzn1V8cH0emcQP55u3ug6c8w9VBB3PdANBp4ma6v1QCsNiq" // Replace this with your API Key

                    let response = await axios.get('https://api.thecatapi.com/v1/images/search', { params: { limit:1, mime_types:"gif" } } ) // Ask for 1 Image, at full resolution

                    this.image = response.data[0] // the response is an Array, so just use the first item as the Image

                    console.log("-- Image from TheCatAPI.com")
                    console.log("id:", this.image.id)
                    console.log("url:", this.image.url)

                }catch(err){
                    console.log(err)
                }
            }
        }
    })
</script>
</body>
</html>
