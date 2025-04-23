const { Sequelize, DataTypes } = require('sequelize');

const sequelize = new Sequelize('projects', 'postgres', 'postgres', {
  host: '127.0.0.1',
  dialect: 'postgres',
  port: 5433,
  logging: false
});

const db = {};
db.Sequelize = Sequelize;
db.sequelize = sequelize;

db.Project = require('./Project')(sequelize, DataTypes);
db.User = require('./User')(sequelize, DataTypes);

db.Project.belongsTo(db.User, { as: 'leader', foreignKey: 'leaderId' }); 
db.Project.belongsToMany(db.User, { through: 'ProjectMembers', as: 'members', foreignKey: 'projectId' });
db.User.belongsToMany(db.Project, { through: 'ProjectMembers', as: 'memberProjects', foreignKey: 'userId' });

sequelize.authenticate()
  .then(() => console.log('Database connected successfully'))
  .catch(err => console.error('Connection error:', err));

module.exports = db;