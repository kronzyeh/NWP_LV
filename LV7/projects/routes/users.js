const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const passport = require('passport');
const { Project, User } = require('../models');

require('../config/passport')(passport);

function ensureAuthenticated(req, res, next) {
  if (req.isAuthenticated()) {
    return next();
  }
  res.redirect('/users/login');
}

function ensureNotAuthenticated(req, res, next) {
  if (!req.isAuthenticated()) {
    return next();
  }
  res.redirect('/projects');
}

router.get('/register', ensureNotAuthenticated, (req, res) => {
  res.render('users/register', { error: null });
});

router.post('/register', ensureNotAuthenticated, async (req, res) => {
  try {
    const { username, password } = req.body;
    const hashedPassword = await bcrypt.hash(password, 10);
    await User.create({ username, password: hashedPassword });
    res.redirect('/users/login');
  } catch (err) {
    res.render('users/register', { error: err.message });
  }
});

router.get('/login', ensureNotAuthenticated, (req, res) => {
  res.render('users/login', { error: null });
});

router.post('/login', ensureNotAuthenticated, passport.authenticate('local', {
  successRedirect: '/projects',
  failureRedirect: '/users/login',
  failureFlash: false
}));

router.get('/logout', ensureAuthenticated, (req, res) => {
  req.logout((err) => {
    if (err) return next(err);
    res.redirect('/users/login');
  });
});

router.get('/leader-projects', ensureAuthenticated, async (req, res) => {
  try {
    const projects = await Project.findAll({
      where: { leaderId: req.user.id, isArchived: false },
      include: [{ model: User, as: 'members' }]
    });
    res.render('users/leader-projects', { projects });
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.get('/member-projects', ensureAuthenticated, async (req, res) => {
  try {
    const projects = await req.user.getMemberProjects({
      where: { isArchived: false },
      include: [{ model: User, as: 'leader' }, { model: User, as: 'members' }]
    });
    res.render('users/member-projects', { projects });
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.post('/member-projects/:id/update-tasks', ensureAuthenticated, async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id, {
      include: [{ model: User, as: 'members' }]
    });
    const isMember = project.members.some(member => member.id === req.user.id);
    if (!isMember) {
      return res.status(403).send('Unauthorized');
    }
    await project.update({ completed_tasks: req.body.completedTasks });
    res.redirect('/users/member-projects');
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.get('/archive', ensureAuthenticated, async (req, res) => {
  try {
    const leaderProjects = await Project.findAll({
      where: { leaderId: req.user.id, isArchived: true },
      include: [{ model: User, as: 'members' }]
    });
    const memberProjects = await req.user.getMemberProjects({
      where: { isArchived: true },
      include: [{ model: User, as: 'leader' }, { model: User, as: 'members' }]
    });
    res.render('users/archive', { leaderProjects, memberProjects });
  } catch (err) {
    res.status(500).send(err.message);
  }
});

module.exports = router;