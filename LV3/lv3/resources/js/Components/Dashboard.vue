<template>
    <div>
      <h2>Welcome to your Dashboard</h2>
  
      <button @click="logout">Logout</button>
  
      <div v-if="projects.length > 0">
        <h3>Your Projects as Owner</h3>
        <ul>
          <li v-for="project in ownerProjects" :key="project.id">
            <h4>{{ project.name }}</h4>
            <p>{{ project.description }}</p>
            <p><strong>Price: </strong>{{ project.price }}</p>
            <p><strong>Start Date: </strong>{{ project.start_date }}</p>
            <p><strong>End Date: </strong>{{ project.end_date }}</p>
            <button @click="viewProject(project.id)">View Project Details</button>
          </li>
        </ul>
  
        <h3>Your Projects as Member</h3>
        <ul>
          <li v-for="project in memberProjects" :key="project.id">
            <h4>{{ project.name }}</h4>
            <p>{{ project.description }}</p>
            <p><strong>Price: </strong>{{ project.price }}</p>
            <p><strong>Start Date: </strong>{{ project.start_date }}</p>
            <p><strong>End Date: </strong>{{ project.end_date }}</p>
            <p>You are a member of this project</p>
          </li>
        </ul>
      </div>
  
      <div v-if="!projects.length">
        <p>You haven't created or joined any projects yet.</p>
      </div>
  
      <div>
        <button @click="showCreateProjectForm = !showCreateProjectForm">Create New Project</button>
        <div v-if="showCreateProjectForm">
          <form @submit.prevent="createProject">
            <div>
              <label for="name">Project Name:</label>
              <input type="text" v-model="newProject.name" required />
            </div>
            <div>
              <label for="description">Description:</label>
              <textarea v-model="newProject.description"></textarea>
            </div>
            <div>
              <label for="price">Price:</label>
              <input type="number" v-model="newProject.price" />
            </div>
            <div>
              <label for="start_date">Start Date:</label>
              <input type="date" v-model="newProject.start_date" />
            </div>
            <div>
              <label for="end_date">End Date:</label>
              <input type="date" v-model="newProject.end_date" />
            </div>
            <div>
              <label for="owner_id">Project Owner:</label>
              <select v-model="newProject.owner_id" required>
                <option v-for="user in users" :key="user.id" :value="user.id">
                  {{ user.name }}
                </option>
              </select>
            </div>
            <button type="submit">Create Project</button>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        projects: [], 
        ownerProjects: [],
        memberProjects: [],
        showCreateProjectForm: false,
        newProject: {
          name: '',
          description: '',
          price: '',
          start_date: '',
          end_date: '',
          owner_id: '',
        },
        users: [], 
      };
    },
    methods: {
      fetchProjects() {
        const token = localStorage.getItem('token');
        if (token) {
          axios.get('/projects', {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          })
          .then(response => {
            this.projects = response.data;
            this.splitProjectsByRole();
          })
          .catch(error => {
            console.error("There was an error fetching the projects:", error);
          });
        }
      },
      fetchUsers() {
        axios.get('/users')
          .then(response => {
            this.users = response.data;
          })
          .catch(error => {
            console.error("There was an error fetching users:", error);
          });
      },
      createProject() {
        axios.post('/projects', this.newProject)
          .then(response => {
            this.projects.push(response.data);
            this.splitProjectsByRole();
            this.resetNewProjectForm();
          })
          .catch(error => {
            console.error("There was an error creating the project:", error);
          });
      },
      resetNewProjectForm() {
        this.newProject = {
          name: '',
          description: '',
          price: '',
          start_date: '',
          end_date: '',
          owner_id: '',
        };
        this.showCreateProjectForm = false;
      },
      viewProject(projectId) {
        this.$router.push(`/projects/${projectId}`);
      },
      logout() {
        localStorage.removeItem('token');
        this.$router.push('/login');
      },
      redirectToRegister() {
        this.$router.push('/api/register');
      },
      splitProjectsByRole() {
        const userId = localStorage.getItem('user_id'); 
  
        this.ownerProjects = this.projects.filter(project => project.owner_id !== userId);
        this.memberProjects = this.projects.filter(project => project.owner_id === userId);
      },
    },
    mounted() {
      const token = localStorage.getItem('token');
      if (token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        this.fetchProjects();
        this.fetchUsers();
      } else {
        console.log("No token found. Redirecting to login.");
        this.$router.push('/login');
      }
    },
  };
  </script>
  