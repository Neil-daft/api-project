var request = new XMLHttpRequest();

// Open a new connection, using the GET request on the URL endpoint
request.open('GET', '/users/3', true);

request.onload = function () {
    var data = JSON.parse(this.response);

        console.log(data);
        var newDiv = document.createElement("div");
        // and give it some content
        var newContent = document.createTextNode(data.data.attributes.email);
        // add the text node to the newly created div
        newDiv.appendChild(newContent);

        var currentDiv = document.getElementById("div1");
        document.body.insertBefore(newDiv, currentDiv);



};

// Send request
request.send();