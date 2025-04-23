const { Sequelize, DataTypes } = require('sequelize');

const sequelize = new Sequelize('projects', 'postgres', 'postgres', {
    host: 'localhost',
    dialect: 'postgres',
    port: 5433, 
    logging: false
  });

const db = {};
db.Sequelize = Sequelize;
db.sequelize = sequelize;

db.Project = require('./Project')(sequelize, DataTypes);
db.TeamMember = require('./TeamMember')(sequelize, DataTypes);

db.Project.hasMany(db.TeamMember, { foreignKey: 'projectId' });
db.TeamMember.belongsTo(db.Project, { foreignKey: 'projectId' });

sequelize.authenticate()
  .then(() => console.log('Database connected successfully'))
  .catch(err => console.error('Connection error:', err));

module.exports = db;