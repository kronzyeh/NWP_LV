const express = require('express');
const router = express.Router();
const { Project, TeamMember } = require('../models');

router.get('/', async (req, res) => {
  try {
    const projects = await Project.findAll();
    res.render('projects/index', { projects });
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.get('/new', (req, res) => {
  res.render('projects/new');
});

router.post('/', async (req, res) => {
  try {
    await Project.create({
      name: req.body.name,
      description: req.body.description,
      price: req.body.price,
      completedTasks: req.body.completedTasks,
      startDate: req.body.startDate,
      endDate: req.body.endDate
    });
    res.redirect('/projects');
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.get('/:id', async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id, {
      include: [{ model: TeamMember, as: 'TeamMembers' }]
    });
    res.render('projects/show', { project });
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.get('/:id/edit', async (req, res) => {
  try {
    const project = await Project.findByPk(req.params.id);
    res.render('projects/edit', { project });
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.post('/:id', async (req, res) => {
  try {
    await Project.update({
      name: req.body.name,
      description: req.body.description,
      price: req.body.price,
      completedTasks: req.body.completedTasks,
      startDate: req.body.startDate,
      endDate: req.body.endDate
    }, {
      where: { id: req.params.id }
    });
    res.redirect(`/projects/${req.params.id}`);
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.post('/:id/delete', async (req, res) => {
  try {
    await Project.destroy({ where: { id: req.params.id } });
    res.redirect('/projects');
  } catch (err) {
    res.status(500).send(err.message);
  }
});

router.post('/:id/add-member', async (req, res) => {
  try {
    await TeamMember.create({
      name: req.body.teamMember,
      projectId: req.params.id
    });
    res.redirect(`/projects/${req.params.id}`);
  } catch (err) {
    res.status(500).send(err.message);
  }
});

module.exports = router;