module.exports = (sequelize, DataTypes) => {
    const TeamMember = sequelize.define('TeamMember', {
      name: {
        type: DataTypes.STRING,
        allowNull: false
      }
    }, {
      tableName: 'TeamMembers'
    });
  
    return TeamMember;
  };