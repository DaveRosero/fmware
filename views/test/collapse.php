<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> <!-- Assuming you have Font Awesome included -->
    <title>Collapsible Menu</title>
</head>
<body>

<div class="container mt-4">
    <div class="accordion" id="genderAccordion">
        <div class="accordion-item">
            <h3 class="accordion-header" id="genderHeading">
                <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#" data-bs-toggle="collapse" data-bs-target="#genderCollapse" aria-expanded="true" aria-controls="genderCollapse">
                    Gender
                    <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
                </a>
            </h3>
            <div id="genderCollapse" class="accordion-collapse collapse show" aria-labelledby="genderHeading" data-bs-parent="#genderAccordion">
                <ul class="list-unstyled pl-3">
                    <li><a class="text-decoration-none" href="#">Men</a></li>
                    <li><a class="text-decoration-none" href="#">Women</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
