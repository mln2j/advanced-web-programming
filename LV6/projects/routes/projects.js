var express = require('express');
var router = express.Router();
var mongoose = require('mongoose');
var Project = mongoose.model('Project');

// GET all projects
router.get('/', async function (req, res, next) {
    try {
        const projects = await Project.find({});
        res.render('projects/index', { projects: projects });
    } catch (err) {
        next(err);
    }
});

// GET new project form
router.get('/new', function (req, res) {
    res.render('projects/new');
});

// POST create project
router.post('/', async function (req, res, next) {
    try {
        const project = new Project({
            naziv: req.body.naziv,
            opis: req.body.opis,
            cijena: req.body.cijena,
            obavljeni_poslovi: req.body.obavljeni_poslovi,
            datum_pocetka: req.body.datum_pocetka,
            datum_zavrsetka: req.body.datum_zavrsetka
        });
        await project.save();
        res.redirect('/projects');
    } catch (err) {
        next(err);
    }
});

// GET show project
router.get('/:id', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        if (!project) return next(new Error('Project not found'));
        res.render('projects/show', { project: project });
    } catch (err) {
        next(err);
    }
});

// GET edit project form
router.get('/:id/edit', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        if (!project) return next(new Error('Project not found'));
        res.render('projects/edit', { project: project });
    } catch (err) {
        next(err);
    }
});

// POST update project
router.post('/:id/edit', async function (req, res, next) {
    try {
        await Project.findByIdAndUpdate(req.params.id, {
            naziv: req.body.naziv,
            opis: req.body.opis,
            cijena: req.body.cijena,
            obavljeni_poslovi: req.body.obavljeni_poslovi,
            datum_pocetka: req.body.datum_pocetka,
            datum_zavrsetka: req.body.datum_zavrsetka
        });
        res.redirect('/projects/' + req.params.id);
    } catch (err) {
        next(err);
    }
});

// POST delete project
router.post('/:id/delete', async function (req, res, next) {
    try {
        await Project.findByIdAndDelete(req.params.id);
        res.redirect('/projects');
    } catch (err) {
        next(err);
    }
});

// POST add team member
router.post('/:id/members', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        if (!project) return next(new Error('Project not found'));
        project.clanovi_tima.push(req.body.member_name);
        await project.save();
        res.redirect('/projects/' + req.params.id);
    } catch (err) {
        next(err);
    }
});

module.exports = router;
