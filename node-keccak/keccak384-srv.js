const SHA3 = require('sha3');
const express = require("express");
const bodyParser = require("body-parser");
const app = express();

app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());

app.post('/',function(request,response)
{
    const d = new SHA3.SHA3Hash(384);
    const hashes = JSON.parse(request.body.hashes);
    for(var i = 0; i < hashes.length; i++) {
        d.update(String.fromCharCode.apply(String, hashes[i]));
    }

    response.end(d.digest('hex'));
});

app.listen(8081, function(){
    console.log("Started on PORT 8081");
});