const API = require( './api' );

const pages = {
	static: [
		{
			url: '/',
			endpoint: API.pages + '2',
			template: 'page'
		},
		{
			url: '/about',
			endpoint: API.pages + '3',
			template: 'page'
		},
		{
			url: '/what-is',
			endpoint: API.pages + '5',
			template: 'page'
		},
		{
			url: '/hangul',
			endpoint: API.pages + '6',
			template: 'page'
		},
		{
			url: '/timeline',
			endpoint: API.pages + '7',
			template: 'page-timeline'
		},
	],

	// pages that cant be looped in routes.js
	other: {
		glossary: {
			endpoint: API.pages + '8',
			template: 'page-alpha'
		},
		noun: {
			endpoint: API.pages + '9',
			template: 'page-alpha'
		},
		people: {
			endpoint: API.pages + '10',
			template: 'page-people'
		},
		sources: {
			endpoint: API.pages + '11',
			template: 'page-sources'
		},
		index: {
			endpoint: API.pages + '12',
			template: 'page-index'
		},
		archives: {
			endpoint: API.pages + '192',
			template: 'page-archives'
		},
		media: {
			endpoint: API.pages + '200',
			template: 'page-media'
		},
	},

	// "See All" pages
	see_all: {
		people: {
			endpoint: API.pages + '231',
		},
		noun: {
			endpoint: API.pages + '232',
		},
		glossary: {
			endpoint: API.pages + '233',
		}
	}
};

module.exports = pages;
