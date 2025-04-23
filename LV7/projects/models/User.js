module.exports = (sequelize, DataTypes) => {
    const User = sequelize.define('User', {
      id: { type: DataTypes.INTEGER, primaryKey: true, autoIncrement: true },
      username: { 
        type: DataTypes.STRING(255), 
        allowNull: false, 
        unique: true,
        validate: { notEmpty: { msg: 'Username is required' } }
      },
      password: { 
        type: DataTypes.STRING, 
        allowNull: false,
        validate: { notEmpty: { msg: 'Password is required' } }
      }
    }, {
      tableName: 'users',
      timestamps: false
    });
  
    return User;
  };