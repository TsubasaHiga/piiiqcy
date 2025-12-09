/**
 * Sample Block - Save Component
 *
 * This component renders the block output for the frontend.
 */

import { RichText, useBlockProps } from '@wordpress/block-editor'
import type { CSSProperties } from 'react'

type TextAlignment = CSSProperties['textAlign']

interface SaveProps {
  attributes: {
    content: string
    alignment: TextAlignment
  }
}

export default function save({ attributes }: SaveProps) {
  const { content, alignment } = attributes
  const blockProps = useBlockProps.save({
    className: `sample-block align-${alignment}`
  })

  return (
    <div {...blockProps}>
      <RichText.Content tagName="p" value={content} style={{ textAlign: alignment }} />
    </div>
  )
}
