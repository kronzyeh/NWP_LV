<template>
    <div class="register-container">
      <h2>Register</h2>
      <form @submit.prevent="register">
        <div>
          <label for="name">Name</label>
          <input type="text" v-model="name" required />
        </div>
        <div>
          <label for="email">Email</label>
          <input type="email" v-model="email" required />
        </div>
        <div>
          <label for="password">Password</label>
          <input type="password" v-model="password" required />
        </div>
        <div>
          <label for="password_confirmation">Confirm Password</label>
          <input type="password" v-model="password_confirmation" required />
        </div>
        <div>
          <button type="submit">Register</button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      };
    },
    methods: {
      async register() {
        try {
          const response = await axios.post("http://localhost:8000/api/register", {
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation,
          });
          localStorage.setItem("token", response.data.token); // Store the JWT token
          this.$router.push("/dashboard"); // Redirect to dashboard after successful registration
        } catch (error) {
          console.error("Registration failed:", error);
        }
      },
    },
  };
  </script>
  
  <style scoped>
  /* Add basic styling for the registration form */
  </style>
  