<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

</head>
<title>TR34 Slab Load Check</title>

<body>
  <div class="container">
    <br>
    <hr>
  <h3 style="text-align: center;">TR34 Slab Load Check</h3>
    <div class="row">
      <div class="col-sm text-auto">

        <form action="tr34.php" method="POST">
          <div class="form-group">
          <label for="conc">Concrete:</label>
          <select class="form-control" name="conc">
            <option disabled selected value>Select</option>
            <option value="a">C25/30</option>
            <option value="b">C28/35</option>
            <option value="c">C30/37</option>
            <option value="d">C32/40</option>
            <option value="e">C35/45</option>
            <option value="f">C40/50</option>
          </select>
        </div>
        <div class="form-group">
          <label for="h">Slab depth (mm):</label>
          <input class="form-control" type="text" name="h"></div>
          <div class="form-group">
          <label for="h">Yield strength (MPa):</label>
          <input class="form-control" type="text" name="fyk"></div>
          <div class="form-group">
          <label for="h">Area of steel (mm2):</label>
          <input class="form-control" type="text" name="As"></div>
          <div class="form-group">
          <label for="h">Bar diameter (mm):</label>
          <input class="form-control" type="text" name="bar_dia"></div>
          <div class="form-group">
          <label for="h">Cover (mm):</label>
          <input class="form-control" type="text" name="cover"></div>
          <div class="form-group">
          <label for="h">Point load (kN):</label>
          <input class="form-control" type="text" name="Qk"></div>
          <div class="form-group">
          <label for="h">Tyre area (mm2):</label>
          <input class="form-control" type="text" name="tyre_area"></div>
          <div class="form-group">
          <label for="h">K30:</label>
          <input class="form-control" type="text" name="k30"></div>
          <div class="form-group">
          <label for="h">Number of loads:</label>
          <input class="form-control" type="text" name="N"></div>
          <div class="form-group">
          <label for="r">Reinforcement:</label>
          <select class="form-control"name="r">
            <option value="r">Reinforced</option>
            <option value="ur">Un-reinforced</option>
          </select></div>
          <Button class="btn btn-primary" type="submit" value="Calculate">Calculate</Button>
        </form>
        <br>
        <br>
        <hr>
        <br>
        <br>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
          integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
          crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
          integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
          crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
          integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
          crossorigin="anonymous"></script>
      </div>
    </div>
  </div>

</body>

</html>