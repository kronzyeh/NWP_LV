<template>
    <div>
      <h2>Projects</h2>
      <button @click="createProject">Create Project</button>
  
      <ul>
        <li v-for="project in projects" :key="project.id">
          <h3>{{ project.name }}</h3>
          <p><strong>Description</strong>:{{ project.description }}</p>
          <p>Price: {{ project.price }}</p>
          <p>Start Date: {{ project.start_date }}</p>
          <p>End Date: {{ project.end_date }}</p>
          <button @click="addMember(project.id)">Add Member</button>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        projects: [],
      };
    },
    methods: {
      createProject() {
      },
      addMember(projectId) {
      
      }
    },
    mounted() {
      axios.get('/api/projects').then(response => {
        this.projects = response.data;
      });
    },
  };
  </script>
  