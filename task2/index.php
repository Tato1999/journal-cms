<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Simple PHP Order — Frontend</title>

<style>
    :root{font-family:system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial;}
    body{
        max-width:900px;
        margin:32px auto;
        padding:18px;
        background:#f7f8fb;
        border-radius:12px;
        box-shadow:0 6px 18px rgba(20,20,40,0.06);
    }

    h1{margin:0 0 12px;font-size:24px}
    h3{margin-top:20px}

    button{
        background:#2b6cb0;
        color:#fff;
        border:0;
        padding:8px 14px;
        border-radius:8px;
        cursor:pointer;
        margin-right:6px;
    }

    button.gray{background:#6b7280}

    button a{
        color:#fff;
        text-decoration:none;
        font-weight:600;
    }

    /* STATISTICS */
    .stats{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:12px;
        margin-top:20px;
    }

    .stat-card{
        background:#fff;
        padding:14px;
        border-radius:8px;
        box-shadow:0 3px 8px rgba(0,0,0,0.03);
    }

    .stat-card span{
        font-size:13px;
        color:#666;
    }

    .stat-card h2{
        margin:6px 0 0;
        font-size:22px;
    }

    /* BAR VISUALIZATION */
    .chart{
        background:#fff;
        border-radius:8px;
        padding:14px;
        margin-top:16px;
        box-shadow:0 3px 8px rgba(0,0,0,0.03);
    }

    .bar{
        margin:10px 0;
    }

    .bar-label{
        font-size:13px;
        margin-bottom:4px;
        color:#444;
    }

    .bar-track{
        background:#e5e7eb;
        border-radius:6px;
        overflow:hidden;
        height:16px;
    }

    .bar-fill{
        height:100%;
        background:#2b6cb0;
        border-radius:6px;
    }

    @media (max-width:600px){
        body{margin:12px;padding:12px}
    }
</style>
</head>

<body>

<h1>Simple Order — Frontend</h1>

<h3>Navigation</h3>
<button class="gray"><a href="add_new_product.php">Add New Product</a></button>
<button class="gray"><a href="home.php">Home Page</a></button>
<button class="gray"><a href="orders.php">Orders Page</a></button>
<button><a href="statistics.php">Statistics</a></button>


</body>
</html>

