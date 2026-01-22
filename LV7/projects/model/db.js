var mongoose = require('mongoose');
mongoose.connect('mongodb://127.0.0.1/projects').then(() => {
    console.log('Successfully connected to MongoDB.');
}).catch(err => {
    console.error('FAILED to connect to MongoDB. Please make sure MongoDB is running.');
    console.error('Error details:', err.message);
});
