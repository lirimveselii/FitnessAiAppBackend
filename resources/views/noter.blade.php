<form method="POST" action="/generate-contract">
    @csrf
    <input type="text" name="name" placeholder="Client Name">
    <input type="text" name="car_model" placeholder="Car Model">
    <button type="submit">Generate Contract</button>
</form>
