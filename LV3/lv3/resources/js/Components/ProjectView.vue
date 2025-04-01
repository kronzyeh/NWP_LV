<template>
    <div v-if="project">
      <h2>{{ project.name }}</h2>
      <p><strong>Description:</strong>{{ project.description }}</p>
      <p><strong>Price:</strong> {{ project.price }}</p>
      <p><strong>Start Date:</strong> {{ project.start_date }}</p>
      <p><strong>End Date:</strong> {{ project.end_date }}</p>
      
      <h3>Add Members</h3>
      <select v-model="selectedMember">
        <option v-for="user in users" :key="user.id" :value="user.id">
          {{ user.name }}
        </option>
      </select>
      <button @click="addMember">Add Member</button>
      
      <h3>Members</h3>
      <ul>
        <li v-for="member in project.members" :key="member.id">{{ member.name }}</li>
      </ul>
    </div>
    <div v-else>
      <p>Loading project details...</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
  data() {
    return {
      project: null,
    };
  },
  methods: {
    fetchProject() {
      const projectId = this.$route.params.id; 
      const token = localStorage.getItem('token'); 

      if (token) {
        axios.get(`/projects/${projectId}`, { headers: { Authorization: `Bearer ${token}` } })
        .then(response => {
          this.project = response.data.project; 
          this.users = response.data.users;
        })
        .catch(error => {
          console.error("There was an error fetching the project:", error);
          if (error.response && error.response.status === 401) {
            alert('Session expired. Please log in again.');
          }
        });
      } else {
        console.log("No token found.");
        alert("Please log in.");
      }
    },
    addMember() {
      const projectId = this.$route.params.id;
      const token = localStorage.getItem('token');

      if (this.selectedMember) {
        axios.post(`/projects/${projectId}/add-member`, { user_id: this.selectedMember }, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        })
        .then(response => {
          const addedUser = this.users.find(user => user.id === this.selectedMember);
          this.project.members.push(addedUser);

          this.users = this.users.filter(user => user.id !== this.selectedMember);
          this.selectedMember = null; 
        })
        .catch(error => {
          console.error("There was an error adding the member:", error);
        });
      }
    },
  },
  mounted() {
    this.fetchProject();
  },
};
  </script>
  