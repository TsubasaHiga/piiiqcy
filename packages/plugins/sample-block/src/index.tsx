/**
 * Sample Block - Entry Point
 *
 * This file registers the custom block with WordPress.
 */

import './blocks/sample-block/style.scss'

import { registerBlockType } from '@wordpress/blocks'

import blockJson from './blocks/sample-block/block.json'
import Edit from './blocks/sample-block/edit'
import save from './blocks/sample-block/save'

// Register the block
registerBlockType(blockJson.name, {
  ...blockJson,
  edit: Edit,
  save
} as any)
