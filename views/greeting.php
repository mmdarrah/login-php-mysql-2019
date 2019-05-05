
<div class="card bg-light mb-3" style="max-width: 18rem;" >

<h1 class="card-header">Add to my journal</h1>

<form action="?action=submit" method="POST" class="form-group card-body">


<label for="title">Title</label>
<br>
<input type="text" name="title">
<br>
<label for="content">Content</label>

<textarea name="content" cols="30" rows="10"></textarea>

<input type="submit" value="Submit" class="btn btn-dark " style="margin-top: 20px">
</form>
  
  <form action="?action=logout" method="post" class="form-group card-body">
         <input type="submit" value="Logout" class="btn btn-danger" style="margin-top: 20px">
</form>

</div>







