<div class="parent">
  <div class="left">
    <img src="/assets/kars.png" alt="Image">
  </div>
  <div class="right">
    <p>Your centered text here</p>
  </div>
</div>

<style>
  .parent {
    display: flex;
    width: 100%;
    height: 300px; /* Adjust as needed */
  }
  .left {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .left img {
    height: 100%; /* Image maintains height of parent */
    width: auto; /* Ensures the div only takes up as much space as the image */
    object-fit: contain; /* Ensures the entire image is visible */
  }
  .right {
    flex: 1; /* Takes up the remaining space */
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    background: lightgray; /* Optional */
  }
</style>
