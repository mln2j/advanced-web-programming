const request = require('supertest');
const app = require('../app');
const mongoose = require('mongoose');
const assert = require('assert');

describe('LV6 Projects CRUD Integration Tests', function () {
    let projectId;

    // Wait for DB connection
    before(function (done) {
        if (mongoose.connection.readyState === 1) {
            done();
        } else {
            mongoose.connection.on('connected', () => done());
        }
    });

    it('1. Should create a new project', async function () {
        const res = await request(app)
            .post('/projects')
            .send({
                naziv: 'Testni automatizirani projekt',
                opis: 'Opis iz testne skripte',
                cijena: 99.99,
                obavljeni_poslovi: 'Ništa još',
                datum_pocetka: '2026-01-01',
                datum_zavrsetka: '2026-12-31'
            });

        // Redirect means success in our implementation
        assert.strictEqual(res.status, 302);
        assert.strictEqual(res.header.location, '/projects');
    });

    it('2. Should list projects and contain the new project', async function () {
        const res = await request(app).get('/projects');
        assert.strictEqual(res.status, 200);
        assert.ok(res.text.includes('Testni automatizirani projekt'));

        // Extract an ID for further tests (since we don't have an API, we parse HTML or query DB)
        const Project = mongoose.model('Project');
        const project = await Project.findOne({ naziv: 'Testni automatizirani projekt' });
        projectId = project._id;
    });

    it('3. Should show project details', async function () {
        const res = await request(app).get(`/projects/${projectId}`);
        assert.strictEqual(res.status, 200);
        assert.ok(res.text.includes('Opis iz testne skripte'));
    });

    it('4. Should add a team member', async function () {
        const res = await request(app)
            .post(`/projects/${projectId}/members`)
            .send({ member_name: 'Robot Tester' });

        assert.strictEqual(res.status, 302);

        const resDetails = await request(app).get(`/projects/${projectId}`);
        assert.ok(resDetails.text.includes('Robot Tester'));
    });

    it('5. Should update project details', async function () {
        const res = await request(app)
            .post(`/projects/${projectId}/edit`)
            .send({
                naziv: 'Ažurirani projekt',
                opis: 'Novi opis',
                cijena: 250,
                obavljeni_poslovi: 'Faza 1 gotova',
                datum_pocetka: '2026-01-01',
                datum_zavrsetka: '2026-11-31'
            });

        assert.strictEqual(res.status, 302);

        const resDetails = await request(app).get(`/projects/${projectId}`);
        assert.ok(resDetails.text.includes('Ažurirani projekt'));
        assert.ok(resDetails.text.includes('250'));
    });

    it('6. Should delete the project', async function () {
        const res = await request(app).post(`/projects/${projectId}/delete`);
        assert.strictEqual(res.status, 302);

        const resList = await request(app).get('/projects');
        assert.ok(!resList.text.includes('Ažurirani projekt'));
    });
});
