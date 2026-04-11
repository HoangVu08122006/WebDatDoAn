<h2>Menu</h2>
<div id="menu"></div>

<script>
fetch('../api/menu.php')
.then(res => res.json())
.then(data => {
    let html = "";
    data.forEach(item => {
        html += `<p>${item.name} - ${item.price}</p>`;
    });
    document.getElementById("menu").innerHTML = html;
});
</script>