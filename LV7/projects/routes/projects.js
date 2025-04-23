const express = require('express');
const router = express.Router();
const { Project, User } = require('../models');

// Middleware to ensure user is authenticated
function ensureAuthenticated(req, res, next) {
  if (req.isAuthenticated()) {
    return next();
  }
  res.redirect('/users/login');
}

router.get('/', ensureAuthenticated, async (req, res) => {
  try {
    const projects = await Project.findAll({
      where: { leaderId: req.user.id, isArchived: false },
      include: [{ model: User, as: 'members' }]
    });
    res.render('projects/index', { projects });
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.get('/new', ensureAuthenticated, (req, res) => {
  res.render('projects/new', { error: null }); 
});

router.post('/', ensureAuthenticated, async (req, res) => {
  try {
    await Project.create({
      name: req.body.name,
      description: req.body.description,
      price: req.body.price,
      completed_tasks: req.body.completedTasks,
      start_date: req.body.startDate,
      end_date: req.body.endDate,
      leaderId: req.user.id,
      isArchived: false
    });
    res.redirect('/projects');
  } catch (err) {
    res.render('projects/new', { error: err.message });
  }
});

router.get('/:id', ensureAuthenticated, async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id, {
      include: [
        { model: User, as: 'leader' },
        { model: User, as: 'members' }
      ]
    });
    if (!project) {
      return res.status(404).send('Project not found');
    }
    const allUsers = await User.findAll();
    res.render('projects/show', { project, allUsers, currentUser: req.user });
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.get('/:id/edit', ensureAuthenticated, async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id);
    if (!project || project.leaderId !== req.user.id) {
      return res.status(403).send('Unauthorized');
    }
    res.render('projects/edit', { project });
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.post('/:id', ensureAuthenticated, async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id);
    if (!project || project.leaderId !== req.user.id) {
      return res.status(403).send('Unauthorized');
    }
    await Project.update({
      name: req.body.name,
      description: req.body.description,
      price: req.body.price,
      completed_tasks: req.body.completedTasks,
      start_date: req.body.startDate,
      end_date: req.body.endDate
    }, {
      where: { id: req.params.id }
    });
    res.redirect(`/projects/${req.params.id}`);
  } catch (err) {
    res.render('projects/edit', { project: req.body, error: err.message });
  }
});

router.post('/:id/delete', ensureAuthenticated, async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id);
    if (!project || project.leaderId !== req.user.id) {
      return res.status(403).send('Unauthorized');
    }
    await Project.destroy({ where: { id: req.params.id } });
    res.redirect('/projects');
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.post('/:id/add-member', ensureAuthenticated, async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id);
    if (!project || project.leaderId !== req.user.id) {
      return res.status(403).send('Unauthorized');
    }
    const user = await User.findByPk(req.body.userId);
    if (!user) {
      return res.status(404).send('User not found');
    }
    await project.addMember(user);
    res.redirect(`/projects/${req.params.id}`);
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.post('/:id/archive', ensureAuthenticated, async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id);
    if (!project || project.leaderId !== req.user.id) {
      return res.status(403).send('Unauthorized');
    }
    await project.update({ isArchived: true });
    res.redirect('/projects');
  } catch (err) {
    res.status(500).send(err.message);
  }
});

module.exports = router;