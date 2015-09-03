# block-selfstudy

Self study management block
========

The Self study management block gives you the ability to create a manage physical and link courses
and will allow your students to request a physical copy of a course.

This block requires another block named "ps_availablecourses"

Features
--------

- Admin can create 2 types of courses, physical and link type.
- Admin can create a course and disable it, so users won't see it in the list.
- Users can request a course and view the list of the courses requested.
- Users can mark as completed the course and see the list of courses completed.
- Admin can view the pending requests.
- Admin can mark as shipped a physical course request when is pending.
- Admin can delete a course.
- Admin can delete a request.

Installation
------------

Install: 

	1. unzip the block ps_selfstudy on your moodlesite/blocks/
    2. login in your site and go to the home, follow the installation process
    3. go to site administration > users > accounts > user profile fields.
    	- Create a text input type with shortname "zipcode"
    	- Create a text input type with shortname "address2"
    4. go to the home page and turn editing on
    5. add the block Self-study.

Contribute
----------

- Issue Tracker: github.com/andrewmrg/ps_selfstudy/issues
- Source Code: github.com/andrewmrg/ps_selfstudy

Support
-------

If you are having issues, please let us know.
Email me at: andrewramos@paradisosolutions.com