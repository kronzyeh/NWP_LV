<template>
    <div class="home">
      <h1>Welcome to the Home Page</h1>
      
      <div>
        <button @click="redirectToLogin">Login</button>
        <button @click="redirectToRegister">Register</button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    methods: {
      // Redirect to the login page
      redirectToLogin() {
        this.$router.push('/login'); 
      },
      
      // Redirect to the registration page
      redirectToRegister() {
        this.$router.push('/register');  
      },
    },
  };
  </script>
  
  <style scoped>
  .home {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
  }
  
  button {
    margin: 10px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
  }
  </style>
  