"use strict";

const types = [
  {
    value: 'build',
    name: 'build:    Changes to the build process or auxiliary tools'
  },
  {
    value: 'docs',
    name: 'docs:     Documentation only changes'
  },
  {
    value: 'feat',
    name: 'feat:     A new feature',
  },
  {
    value: 'fix',
    name: 'fix:      A bug fix',
  },
  {
    value: 'docs',
    name: 'docs:     Documentation only changes',
  },
  {
    value: 'refactor',
    name: 'refactor: A code change that neither fixes a bug nor adds a feature',
  },
  {
    value: 'perf',
    name: 'perf:     A code change that improves performance',
  },
  {
    value: 'revert',
    name: 'revert:   Revert a commit',
  },
  {
    value: 'style',
    name: `style:    Changes that do not affect the meaning of the code
            (white-space, formatting, missing semi-colons, etc)`,
  },
  {
    value: 'test',
    name: 'test:     Adding missing tests',
  },
];

const scopes = ['core', 'installer', 'package', 'manager', 'lexicon'].map(name => ({
  name
}));
module.exports = {
  types,
  scopes,
  allowCustomScopes: true,
  allowBreakingChanges: false,
  footerPrefix: "META:",
  subjectLimit: 72
};
