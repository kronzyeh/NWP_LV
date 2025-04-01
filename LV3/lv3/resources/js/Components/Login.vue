<template>
    <div class="login-container">
      <h2>Login</h2>
      <form @submit.prevent="login">
        <div>
          <label for="email">Email</label>
          <input type="email" v-model="email" required />
        </div>
        <div>
          <label for="password">Password</label>
          <input type="password" v-model="password" required />
        </div>
        <div>
          <button type="submit">Login</button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        email: "",
        password: "",
      };
    },
    methods: {
      async login() {
        try {
          const response = await axios.post("http://localhost:8000/api/login", {
            email: this.email,
            password: this.password,
          });
          localStorage.setItem("token", response.data.token);
          localStorage.setItem("user_id", response.data.user.id);
          this.$router.push("/dashboard");
        } catch (error) {
          console.error("Login failed:", error);
        }
      },
    },
  };
  </script>
  
  <style scoped>
  </style>
  