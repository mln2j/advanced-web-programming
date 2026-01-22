var express = require('express');
var router = express.Router();
var mongoose = require('mongoose');
var Project = mongoose.model('Project');
var User = mongoose.model('User');

// Middleware to check if user is logged in
function ensureAuthenticated(req, res, next) {
    if (req.isAuthenticated()) {
        return next();
    }
    res.redirect('/auth/login');
}

router.use(ensureAuthenticated);

// GET all projects (General view, redirected to lead by default)
router.get('/', function (req, res) {
    res.redirect('/projects/lead');
});

// GET projects I lead
router.get('/lead', async function (req, res, next) {
    try {
        const projects = await Project.find({ voditelj: req.user._id, arhiviran: false });
        res.render('projects/index', { projects, title: 'Projekti kojima sam voditelj' });
    } catch (err) {
        next(err);
    }
});

// GET projects where I am a member
router.get('/member', async function (req, res, next) {
    try {
        const projects = await Project.find({ clanovi_tima: req.user._id, arhiviran: false }).populate('voditelj');
        res.render('projects/index', { projects, title: 'Projekti na kojima sam član' });
    } catch (err) {
        next(err);
    }
});

// GET archived projects
router.get('/archive', async function (req, res, next) {
    try {
        const projects = await Project.find({
            $or: [
                { voditelj: req.user._id },
                { clanovi_tima: req.user._id }
            ],
            arhiviran: true
        }).populate('voditelj');
        res.render('projects/index', { projects, title: 'Arhiva projekata' });
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
            datum_zavrsetka: req.body.datum_zavrsetka,
            voditelj: req.user._id
        });
        await project.save();
        res.redirect('/projects/lead');
    } catch (err) {
        next(err);
    }
});

// GET show project
router.get('/:id', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id)
            .populate('voditelj')
            .populate('clanovi_tima');

        if (!project) return next(new Error('Project not found'));

        // Check if user is lead or member
        const isLead = project.voditelj._id.toString() === req.user._id.toString();
        const isMember = project.clanovi_tima.some(m => m._id.toString() === req.user._id.toString());

        if (!isLead && !isMember) return res.status(403).send('Nemate pristup ovom projektu');

        const users = await User.find({ _id: { $ne: req.user._id } }); // All users except current for adding members

        res.render('projects/show', { project, isLead, isMember, users });
    } catch (err) {
        next(err);
    }
});

// GET edit project form
router.get('/:id/edit', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        if (!project) return next(new Error('Project not found'));

        if (project.voditelj.toString() !== req.user._id.toString()) {
            return res.status(403).send('Samo voditelj može uređivati detalje projekta');
        }

        res.render('projects/edit', { project });
    } catch (err) {
        next(err);
    }
});

// POST update project (Lead edits all, Member only obavljeni_poslovi)
router.post('/:id/edit', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        if (!project) return next(new Error('Project not found'));

        const isLead = project.voditelj.toString() === req.user._id.toString();
        const isMember = project.clanovi_tima.some(m => m.toString() === req.user._id.toString());

        if (isLead) {
            project.naziv = req.body.naziv;
            project.opis = req.body.opis;
            project.cijena = req.body.cijena;
            project.obavljeni_poslovi = req.body.obavljeni_poslovi;
            project.datum_pocetka = req.body.datum_pocetka;
            project.datum_zavrsetka = req.body.datum_zavrsetka;
        } else if (isMember) {
            project.obavljeni_poslovi = req.body.obavljeni_poslovi;
        } else {
            return res.status(403).send('Nemate ovlasti za uređivanje');
        }

        await project.save();
        res.redirect('/projects/' + req.params.id);
    } catch (err) {
        next(err);
    }
});

// POST archive project
router.post('/:id/archive', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        if (project.voditelj.toString() !== req.user._id.toString()) {
            return res.status(403).send('Samo voditelj može arhivirati projekt');
        }
        project.arhiviran = !project.arhiviran;
        await project.save();
        res.redirect('/projects/' + (project.arhiviran ? 'archive' : 'lead'));
    } catch (err) {
        next(err);
    }
});

// POST delete project
router.post('/:id/delete', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        if (project.voditelj.toString() !== req.user._id.toString()) {
            return res.status(403).send('Samo voditelj može obrisati projekt');
        }
        await Project.findByIdAndDelete(req.params.id);
        res.redirect('/projects/lead');
    } catch (err) {
        next(err);
    }
});

// POST add team member (Reference to User)
router.post('/:id/members', async function (req, res, next) {
    try {
        const project = await Project.findById(req.params.id);
        if (project.voditelj.toString() !== req.user._id.toString()) {
            return res.status(403).send('Samo voditelj može dodavati članove');
        }

        const userId = req.body.user_id;
        if (!project.clanovi_tima.includes(userId)) {
            project.clanovi_tima.push(userId);
            await project.save();
        }
        res.redirect('/projects/' + req.params.id);
    } catch (err) {
        next(err);
    }
});

module.exports = router;
