/**
 * Sample Block - Edit Component
 *
 * This component renders the block editor interface.
 */

import { AlignmentToolbar, BlockControls, RichText, useBlockProps } from '@wordpress/block-editor'
import { __ } from '@wordpress/i18n'
import type { CSSProperties } from 'react'

type TextAlignment = CSSProperties['textAlign']

interface EditProps {
  attributes: {
    content: string
    alignment: TextAlignment
  }
  setAttributes: (attributes: Partial<EditProps['attributes']>) => void
}

export default function Edit({ attributes, setAttributes }: EditProps) {
  const { content, alignment } = attributes
  const blockProps = useBlockProps({
    className: `sample-block align-${alignment}`
  })

  return (
    <>
      <BlockControls>
        <AlignmentToolbar
          value={alignment}
          onChange={(newAlignment: string) => setAttributes({ alignment: newAlignment as TextAlignment })}
        />
      </BlockControls>
      <div {...blockProps}>
        <RichText
          tagName="p"
          value={content}
          onChange={(newContent: string) => setAttributes({ content: newContent })}
          placeholder={__('Enter your text here...', 'sample-block')}
          style={{ textAlign: alignment }}
        />
      </div>
    </>
  )
}
