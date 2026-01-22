var express = require('express');
var router = express.Router();
var mongoose = require('mongoose');
var User = mongoose.model('User');
var passport = require('passport');
var bcrypt = require('bcryptjs');

// Registration page
router.get('/register', function (req, res) {
    res.render('auth/register');
});

// Registration logic
router.post('/register', async function (req, res, next) {
    try {
        const { username, password, name } = req.body;
        const hashedPassword = bcrypt.hashSync(password, 10);
        const user = new User({ username, password: hashedPassword, name });
        await user.save();
        res.redirect('/auth/login');
    } catch (err) {
        next(err);
    }
});

// Login page
router.get('/login', function (req, res) {
    res.render('auth/login', { message: req.flash('error') });
});

// Login logic
router.post('/login', passport.authenticate('local', {
    successRedirect: '/projects',
    failureRedirect: '/auth/login',
    failureFlash: true
}));

// Logout
router.get('/logout', function (req, res) {
    req.logout(function (err) {
        if (err) { return next(err); }
        res.redirect('/auth/login');
    });
});

module.exports = router;
