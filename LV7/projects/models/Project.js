module.exports = (sequelize, DataTypes) => {
  const Project = sequelize.define('Project', {
    id: { type: DataTypes.INTEGER, primaryKey: true, autoIncrement: true },
    name: { 
      type: DataTypes.STRING(255), 
      allowNull: false,
      validate: { notEmpty: { msg: 'Name is required' } }
    },
    description: { type: DataTypes.TEXT, allowNull: true },
    price: { 
      type: DataTypes.DECIMAL(10, 2), 
      allowNull: false,
      validate: { min: { args: [0], msg: 'Price must be a positive number' } }
    },
    completed_tasks: { type: DataTypes.TEXT, allowNull: true },
    start_date: { 
      type: DataTypes.DATEONLY, 
      allowNull: false,
      validate: { isDate: { msg: 'Start date must be a valid date' } }
    },
    end_date: { 
      type: DataTypes.DATEONLY, 
      allowNull: true,
      validate: { 
        isDate: { msg: 'End date must be a valid date' },
        isAfterStart(value) {
          if (value && this.start_date && new Date(value) < new Date(this.start_date)) {
            throw new Error('End date must be after start date');
          }
        }
      }
    },
    leaderId: { 
      type: DataTypes.INTEGER, 
      allowNull: false,
      references: { model: 'users', key: 'id' }
    },
    isArchived: { 
      type: DataTypes.BOOLEAN, 
      allowNull: false, 
      defaultValue: false 
    }
  }, {
    tableName: 'projects',
    timestamps: false
  });

  return Project;
};