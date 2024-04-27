<?php
header("Content-type: text/css; charset: UTF-8");

echo '
table { 
    width: 100%; 
    border-collapse: collapse; 
}
th, td { 
    padding: 8px; 
    text-align: left; 
    border-bottom: 1px solid #ddd; 
}
th { 
    background-color: #f2f2f2; 
}
.receipt { 
    margin: 20px auto; 
    max-width: 400px; 
    page-break-after: always; 
}
.total { 
    font-weight: bold; 
    text-align: right; 
    margin-top: 10px; 
}
.header { 
    text-align: center; 
    margin-bottom: 20px; 
}
.logo { 
    width: 100px; 
    height: 100px; 
    margin-bottom: 10px; 
}
.center { 
    text-align: center; 
}
.signature { 
    margin-top: 30px; 
}
.signature p { 
    text-align: center; 
    margin-top: 50px; 
}
.vat { 
    text-align: right; 
}
.discount { 
    text-align: right; 
}
';
?>